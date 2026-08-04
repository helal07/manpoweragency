<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\JobApplicationFieldValue;
use App\Models\JobCircular;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function index()
    {
        $applications = JobApplication::with(['jobCircular', 'customFieldValues.customField'])
            ->where('applicant_id', auth()->id())
            ->latest()
            ->get();
            
        return view('applicant.applications.index', compact('applications'));
    }
    
    public function show(JobApplication $application)
    {
        if ($application->applicant_id !== auth()->id()) {
            abort(403);
        }
        
        $application->load(['jobCircular.customFields', 'customFieldValues.customField']);
        return view('applicant.applications.show', compact('application'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'job_circular_id' => 'required|exists:job_circulars,id',
            'cover_letter' => 'nullable|string|max:2000',
        ]);
        
        $circular = JobCircular::with('customFields')->findOrFail($request->job_circular_id);

        // Ensure they haven't applied already
        $existing = JobApplication::where('applicant_id', auth()->id())
            ->where('job_circular_id', $circular->id)
            ->first();
            
        if ($existing) {
            return back()->with('error', 'You have already applied for this position.');
        }

        // Build dynamic validation rules and custom attribute names for attached custom fields
        $rules = [];
        $customAttributes = [];

        foreach ($circular->customFields as $field) {
            $key = "custom_fields.{$field->id}";
            $isRequired = (bool) ($field->pivot->is_required ?? false);

            $fieldRules = [$isRequired ? 'required' : 'nullable'];

            if ($field->type === 'file') {
                $fieldRules[] = 'file';
                $fieldRules[] = 'mimes:pdf,doc,docx,jpg,jpeg,png,webp';
                $fieldRules[] = 'max:10240'; // 10MB
            } elseif ($field->type === 'number') {
                $fieldRules[] = 'numeric';
            } elseif ($field->type === 'date') {
                $fieldRules[] = 'date';
            } elseif ($field->type === 'checkbox') {
                $fieldRules = ['nullable', 'boolean'];
            } else {
                $fieldRules[] = 'string';
                $fieldRules[] = 'max:2000';
            }

            $rules[$key] = $fieldRules;
            $customAttributes[$key] = $field->label;
        }

        $validated = $request->validate($rules, [], $customAttributes);

        // Create the Job Application
        $application = JobApplication::create([
            'applicant_id' => auth()->id(),
            'job_circular_id' => $circular->id,
            'status' => 'pending',
            'cover_letter' => $request->input('cover_letter'),
        ]);

        // Save submitted custom field values & uploaded files
        foreach ($circular->customFields as $field) {
            $valueToSave = null;

            if ($field->type === 'file') {
                if ($request->hasFile("custom_fields.{$field->id}")) {
                    $file = $request->file("custom_fields.{$field->id}");
                    $path = $file->store('application_documents', 'public');
                    $valueToSave = $path;
                }
            } elseif ($field->type === 'checkbox') {
                $valueToSave = $request->has("custom_fields.{$field->id}") ? '1' : '0';
            } else {
                $valueToSave = $request->input("custom_fields.{$field->id}");
            }

            if ($valueToSave !== null && $valueToSave !== '') {
                JobApplicationFieldValue::create([
                    'job_application_id' => $application->id,
                    'custom_field_id' => $field->id,
                    'value' => (string) $valueToSave,
                ]);
            }
        }

        return back()->with('success', 'Your application and required documents have been submitted successfully!');
    }
}
