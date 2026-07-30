<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
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
        return view('profile.edit', [
            'user' => $request->user(),
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

        // Validate profile info
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                \Illuminate\Validation\Rule::unique(get_class($user))->ignore($user->id),
            ],
            'phone' => ['nullable', 'string', 'max:255'],
            'current_address' => ['nullable', 'string'],
            'permanent_address' => ['nullable', 'string'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
        ]);

        $user->fill($validated);
        
        // Add new profile fields
        $user->phone = $request->input('phone');
        $user->current_address = $request->input('current_address');
        $user->permanent_address = $request->input('permanent_address');
        $user->linkedin_url = $request->input('linkedin_url');

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

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
