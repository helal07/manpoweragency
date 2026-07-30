@extends('layouts.applicant')

@section('title', 'My Profile')
@section('header_title', 'Profile Management')

@section('content')
<div class="space-y-6 max-w-4xl">
    
    <!-- Profile Picture & Resume Section -->
    <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-slate-200">
        <h3 class="text-lg font-bold text-slate-800 mb-6">Profile Assets</h3>
        
        <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="flex flex-col sm:flex-row gap-8 items-start">
            @csrf
            @method('patch')
            <input type="hidden" name="update_assets" value="1">

            <!-- Avatar -->
            <div class="flex flex-col items-center gap-3">
                <div class="relative w-32 h-32 rounded-full border-4 border-slate-100 bg-slate-50 overflow-hidden flex items-center justify-center text-4xl font-bold text-slate-300">
                    @if(auth()->user()->getFirstM