@extends('layouts.applicant')

@section('title', 'Application Details')
@section('header_title', 'Application Details')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-4 mb-2">
        <a href="{{ route('applications.index') }}" class="text-slate-500 hover:text-slate-700 bg-white p-2 rounded-lg border border-slate-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <h2 class="text-xl font-bold text-slate-800">Application Tracking</h2>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Main Timeline & Details -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Job Info Card -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex flex-col md:flex-row justify-between gap-4">
                <div>
                    <h3 class="text-2xl font-bold text-slate-800">{{ $application->jobCircular->title }}</h3>
                    <p class="text-slate-500 mt-1">{{ $application->jobCircular->employer_name ?? 'Confidential Employer' }} &bull; Applied on {{ $application->created_at->format('M d, Y') }}</p>
                </div>
                <div class="shrink-0">
                    <a href="{{ route('circulars.show', $application->jobCircular->slug) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-50 text-slate-700 font-semibold rounded-lg border border-slate-200 hover:bg-slate-100 transition-colors">
                        View Job Post
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </a>
                </div>
            </div>

            <!-- Status Timeline -->
            <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200 shadow-sm">
                <h4 class="text-lg font-bold text-slate-800 mb-6">Application Progress</h4>
                
                <div class="relative border-l-2 border-slate-200 ml-3 md:ml-4 space-y-8">
                    
                    <!-- Step 1: Applied -->
                    <div class="relative pl-6 md:pl-8">
                        <div class="absolute w-6 h-6 bg-blue-600 rounded-full border-4 border-white -left-[13px] top-1 flex items-center justify-center">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <h5 class="text-slate-800 font-bold">Application Submitted</h5>
                        <p class="text-sm text-slate-500 mt-1">{{ $application->created_at->format('M d, Y h:i A') }}</p>
                        <p class="text-sm text-slate-600 mt-2 bg-slate-50 p-3 rounded-lg border border-slate-100">Your application and resume have been successfully submitted and are awaiting review by the HR team.</p>
                    </div>

                    <!-- Step 2: Under Review -->
                    @php
                        $isReviewed = in_array($application->status, ['reviewed', 'shortlisted', 'interview', 'accepted', 'rejected']);
                        $isShortlisted = in_array($application->status, ['shortlisted', 'interview', 'accepted']);
                        $isInterview = in_array($application->status, ['interview', 'accepted']);
                        $isAccepted = $application->status === 'accepted';
                        $isRejected = $application->status === 'rejected';
                    @endphp

                    <div class="relative pl-6 md:pl-8">
                        <div class="absolute w-6 h-6 {{ $isReviewed ? 'bg-blue-600' : 'bg-slate-200' }} rounded-full border-4 border-white -left-[13px] top-1 flex items-center justify-center">
                            @if($isReviewed)
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            @endif
                        </div>
                        <h5 class="{{ $isReviewed ? 'text-slate-800' : 'text-slate-400' }} font-bold">Application Reviewed</h5>
                        @if($isReviewed)
                            <p class="text-sm text-slate-600 mt-2 bg-slate-50 p-3 rounded-lg border border-slate-100">The recruiting team has reviewed your profile.</p>
                        @endif
                    </div>

                    <!-- Step 3: Shortlisted / Interview -->
                    @if(!$isRejected)
                    <div class="relative pl-6 md:pl-8">
                        <div class="absolute w-6 h-6 {{ $isInterview ? 'bg-blue-600' : 'bg-slate-200' }} rounded-full border-4 border-white -left-[13px] top-1 flex items-center justify-center">
                            @if($isInterview)
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            @endif
                        </div>
                        <h5 class="{{ $isInterview ? 'text-slate-800' : 'text-slate-400' }} font-bold">Interview Scheduled</h5>
                        @if($isInterview)
                            <p class="text-sm text-slate-600 mt-2 bg-blue-50 p-3 rounded-lg border border-blue-100 text-blue-800">You have been selected for an interview. Please check your email for the schedule and instructions.</p>
                        @endif
                    </div>
                    
                    <!-- Step 4: Accepted -->
                    <div class="relative pl-6 md:pl-8">
                        <div class="absolute w-6 h-6 {{ $isAccepted ? 'bg-emerald-500' : 'bg-slate-200' }} rounded-full border-4 border-white -left-[13px] top-1 flex items-center justify-center">
                            @if($isAccepted)
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            @endif
                        </div>
                        <h5 class="{{ $isAccepted ? 'text-emerald-700' : 'text-slate-400' }} font-bold">Job Offer / Accepted</h5>
                        @if($isAccepted)
                            <p class="text-sm text-emerald-700 mt-2 bg-emerald-50 p-3 rounded-lg border border-emerald-100 font-medium">Congratulations! You have been accepted for this position. Our team will contact you shortly.</p>
                        @endif
                    </div>
                    @else
                    <!-- Rejected State -->
                    <div class="relative pl-6 md:pl-8">
                        <div class="absolute w-6 h-6 bg-rose-500 rounded-full border-4 border-white -left-[13px] top-1 flex items-center justify-center">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                        </div>
                        <h5 class="text-rose-700 font-bold">Application Unsuccessful</h5>
                        <p class="text-sm text-rose-700 mt-2 bg-rose-50 p-3 rounded-lg border border-rose-100 font-medium">Unfortunately, we will not be moving forward with your application at this time. We wish you the best in your job search.</p>
                    </div>
                    @endif

                </div>
            </div>
            
        </div>
        
        <!-- Sidebar Details -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <h4 class="font-bold text-slate-800 mb-4">Application Information</h4>
                
                <div class="space-y-4">
                    <div>
                        <div class="text-xs text-slate-500 uppercase font-semibold">Applicant Name</div>
                        <div class="text-sm font-medium text-slate-800 mt-0.5">{{ $application->user->name }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-slate-500 uppercase font-semibold">Resume Used</div>
                        <div class="text-sm font-medium text-blue-600 mt-0.5">
                            @if($application->user->getFirstMediaUrl('resume'))
                                <a href="{{ $application->user->getFirstMediaUrl('resume') }}" target="_blank" class="hover:underline flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                    View Attached Resume
                                </a>
                            @else
                                No resume attached at time of application
                            @endif
                        </div>
                    </div>
                    @if($application->cover_letter)
                    <div>
                        <div class="text-xs text-slate-500 uppercase font-semibold mb-1">Cover Letter Message</div>
                        <div class="text-sm text-slate-700 bg-slate-50 p-3 rounded border border-slate-100 italic">
                            "{{ $application->cover_letter }}"
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            
            <div class="bg-slate-900 rounded-2xl p-6 text-white shadow-sm">
                <h4 class="font-bold mb-2 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Need Help?
                </h4>
                <p class="text-sm text-slate-300 mb-4">If you have any questions regarding your application or interview schedule, please contact our support team.</p>
                <a href="mailto:{{ $siteSettings['company_email'] ?? 'support@example.com' }}" class="block text-center w-full bg-blue-600 hover:bg-blue-500 text-white font-semibold py-2 rounded-lg transition-colors text-sm">Contact Support</a>
            </div>
        </div>

    </div>
</div>
@endsection
