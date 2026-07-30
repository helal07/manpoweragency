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
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        
        // Check if this is an asset update
        if ($request->has('update_assets')) {
            if ($request->hasFile('avatar')) {
                $user->addMediaFromRequest('avatar')
                     ->toMediaCollection('avatar');
            }
            if ($request->hasFile('resume')) {
                $user->addMediaFromRequest('resume')
                     ->toMediaCollection('resume');
            }
            return Redirect::route('profile.edit')->with('status', 'profile-updated');
        }

        $user->fill($request->validated());
        
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
