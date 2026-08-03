<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\CustomField;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $customFields = CustomField::active()->get();
        $user = $request->user();

        // Preload custom field values keyed by custom_field_id
        $customFieldValues = $user->customFieldValues()
            ->pluck('value', 'custom_field_id')
            ->toArray();

        return view('profile.edit', [
            'user' => $user,
            'customFields' => $customFields,
            'customFieldValues' => $customFieldValues,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        
        // Check if this is an asset update
        if ($request->has('update_assets')) {
            $request->validate([
                'avatar' => ['nullable', 'image', 'max:2048'],
                'resume' => ['nullable', 'mimes:pdf,doc,docx', 'max:5120'],
            ]);

            $updated = false;
            if ($request->hasFile('avatar')) {
                $user->clearMediaCollection('avatar');
                $user->addMediaFromRequest('avatar')->toMediaCollection('avatar');
                $updated = true;
            }
            if ($request->hasFile('resume')) {
                $user->clearMediaCollection('resume');
                $user->addMediaFromRequest('resume')->toMediaCollection('resume');
                $updated = true;
            }
            
            if (!$updated) {
                return Redirect::back()->withErrors(['avatar' => 'Please select a valid file to upload. Ensure it is under the size limit.']);
            }
            return Redirect::route('profile.edit')->with('status', 'assets-updated');
        }

        // ── Build validation rules ──
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 'string', 'lowercase', 'email', 'max:255',
                \Illuminate\Validation\Rule::unique(get_class($user))->ignore($user->id),
            ],
            'phone' => ['nullable', 'string', 'max:255'],
            'current_address' => ['nullable', 'string'],
            'permanent_address' => ['nullable', 'string'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            // New fixed fields
            'fathers_name' => ['required', 'string', 'max:255'],
            'mothers_name' => ['nullable', 'string', 'max:255'],
            'mobile_no' => ['required', 'string', 'max:20'],
            'date_of_birth' => ['nullable', 'date'],
            'gender' => ['nullable', 'in:male,female,other'],
            'marital_status' => ['nullable', 'in:single,married,divorced,widowed'],
            'ssc_year' => ['nullable', 'integer', 'min:1950', 'max:' . date('Y')],
            'ssc_result' => ['nullable', 'string', 'max:50'],
            'hsc_year' => ['nullable', 'integer', 'min:1950', 'max:' . date('Y')],
            'hsc_result' => ['nullable', 'string', 'max:50'],
            'highest_education' => ['nullable', 'string', 'max:255'],
            'experience_details' => ['nullable', 'string'],
            'experience_years' => ['nullable', 'integer', 'min:0', 'max:50'],
            'can_speak_english' => ['nullable'],
            'english_proficiency' => ['nullable', 'in:basic,conversational,fluent,native'],
            'other_languages' => ['nullable', 'string', 'max:255'],
            'preferred_country' => ['nullable', 'string', 'max:255'],
            'passport_expiry' => ['nullable', 'date'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:20'],
        ];

        // ── Dynamic custom field validation ──
        $customFields = CustomField::active()->get();
        foreach ($customFields as $field) {
            $fieldRules = [];
            if ($field->is_required) {
                $fieldRules[] = 'required';
            } else {
                $fieldRules[] = 'nullable';
            }

            match ($field->type) {
                'number' => $fieldRules[] = 'numeric',
                'date' => $fieldRules[] = 'date',
                'select' => $fieldRules[] = 'in:' . implode(',', $field->options ?? []),
                'file' => $fieldRules = array_merge($fieldRules, ['file', 'max:5120']),
                default => $fieldRules[] = 'string',
            };

            $rules["custom_field.{$field->id}"] = $fieldRules;
        }

        $validated = $request->validate($rules);

        // ── Fill fixed fields ──
        $fixedFields = [
            'name', 'email', 'phone', 'current_address', 'permanent_address', 'linkedin_url',
            'fathers_name', 'mothers_name', 'mobile_no', 'date_of_birth', 'gender', 'marital_status',
            'ssc_year', 'ssc_result', 'hsc_year', 'hsc_result', 'highest_education',
            'experience_details', 'experience_years', 'english_proficiency', 'other_languages',
            'preferred_country', 'passport_expiry', 'emergency_contact_name', 'emergency_contact_phone',
        ];

        foreach ($fixedFields as $field) {
            if (array_key_exists($field, $validated)) {
                $user->{$field} = $validated[$field];
            }
        }

        // Handle boolean checkbox
        $user->can_speak_english = $request->boolean('can_speak_english');

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // ── Save custom field values ──
        $customFieldData = $request->input('custom_field', []);
        foreach ($customFields as $field) {
            $value = $customFieldData[$field->id] ?? null;

            // Handle checkbox type
            if ($field->type === 'checkbox') {
                $value = isset($customFieldData[$field->id]) ? '1' : '0';
            }

            // Handle file type
            if ($field->type === 'file' && $request->hasFile("custom_field.{$field->id}")) {
                $path = $request->file("custom_field.{$field->id}")->store('custom-field-uploads', 'public');
                $value = $path;
            }

            $user->customFieldValues()->updateOrCreate(
                ['custom_field_id' => $field->id],
                ['value' => $value]
            );
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
