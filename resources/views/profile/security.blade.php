@extends('layouts.applicant')

@section('title', 'Security Settings')
@section('header_title', 'Security & Account')

@section('content')
<div class="space-y-6 max-w-4xl">
    
    <!-- Change Password -->
    <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-slate-200">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center text-slate-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-slate-800">Change Password</h3>
                <p class="text-sm text-slate-500">Ensure your account is using a long, random password to stay secure.</p>
            </div>
        </div>

        <form method="post" action="{{ route('password.update') }}" class="space-y-6">
            @csrf
            @method('put')

            <div class="space-y-4 max-w-md">
                <div>
                    <label for="update_password_current_password" class="block text-sm font-semibold text-slate-700 mb-1">Current Password</label>
                    <input type="password" id="update_password_current_password" name="current_password" autocomplete="current-password" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                </div>

                <div>
                    <label for="update_password_password" class="block text-sm font-semibold text-slate-700 mb-1">New Password</label>
                    <input type="password" id="update_password_password" name="password" autocomplete="new-password" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                </div>

                <div>
                    <label for="update_password_password_confirmation" class="block text-sm font-semibold text-slate-700 mb-1">Confirm Password</label>
                    <input type="password" id="update_password_password_confirmation" name="password_confirmation" autocomplete="new-password" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                </div>
            </div>

            <div class="flex items-center gap-4 pt-4 border-t border-slate-100">
                <button type="submit" class="px-6 py-2.5 bg-slate-900 text-white font-bold rounded-xl hover:bg-slate-800 transition-colors shadow-md">
                    Update Password
                </button>
                @if (session('status') === 'password-updated')
                    <p class="text-sm text-emerald-600 font-semibold flex items-center gap-1" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Saved.
                    </p>
                @endif
            </div>
        </form>
    </div>

    <!-- Notification Preferences -->
    <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-slate-200">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-slate-800">Email Notifications</h3>
                <p class="text-sm text-slate-500">Manage what alerts you receive in your inbox.</p>
            </div>
        </div>

        <form method="post" action="#" class="space-y-4 max-w-lg">
            @csrf
            
            <div class="flex items-center justify-between py-3 border-b border-slate-100">
                <div>
                    <div class="text-sm font-semibold text-slate-700">Application Status Updates</div>
                    <div class="text-xs text-slate-500 mt-0.5">Receive an email when your application status changes.</div>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="notify_status" class="sr-only peer" checked>
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                </label>
            </div>
            
            <div class="flex items-center justify-between py-3 border-b border-slate-100">
                <div>
                    <div class="text-sm font-semibold text-slate-700">New Job Alerts</div>
                    <div class="text-xs text-slate-500 mt-0.5">Receive an email when new jobs matching your skills are posted.</div>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="notify_jobs" class="sr-only peer">
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                </label>
            </div>

            <div class="pt-4">
                <button type="button" onclick="alert('Notification preferences saved successfully.')" class="px-5 py-2 bg-white border border-slate-200 text-slate-700 font-bold rounded-xl hover:bg-slate-50 transition-colors shadow-sm">
                    Save Preferences
                </button>
            </div>
        </form>
    </div>

    <!-- Delete Account -->
    <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-slate-200">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-lg bg-rose-50 flex items-center justify-center text-rose-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-slate-800">Delete Account</h3>
                <p class="text-sm text-slate-500">Permanently delete your account and all associated data.</p>
            </div>
        </div>

        <div class="max-w-xl text-sm text-slate-600 mb-6">
            Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.
        </div>
        
        <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')" class="px-5 py-2.5 bg-rose-600 text-white font-bold rounded-xl hover:bg-rose-700 transition-colors shadow-md">
            Delete Account
        </button>
        
        <!-- Delete Account Modal -->
        <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
            <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
                @csrf
                @method('delete')

                <h2 class="text-lg font-bold text-slate-900">
                    Are you sure you want to delete your account?
                </h2>

                <p class="mt-2 text-sm text-slate-600">
                    Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.
                </p>

                <div class="mt-6 max-w-md">
                    <label for="password" class="sr-only">Password</label>
                    <input type="password" id="password" name="password" placeholder="Password" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-rose-500 focus:ring-rose-500">
                    <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 bg-white border border-slate-300 text-slate-700 font-semibold rounded-lg hover:bg-slate-50">
                        Cancel
                    </button>

                    <button type="submit" class="px-4 py-2 bg-rose-600 text-white font-semibold rounded-lg hover:bg-rose-700">
                        Delete Account
                    </button>
                </div>
            </form>
        </x-modal>
    </div>

</div>
@endsection
