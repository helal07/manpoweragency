<?php

namespace App\Policies;

use App\Models\JobApplication;
use App\Models\User;

class JobApplicationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view_job_applications');
    }

    public function view(User $user, JobApplication $jobApplication): bool
    {
        return $user->can('view_job_applications');
    }

    public function create(User $user): bool
    {
        return false; // Job applications are submitted by applicants from frontend
    }

    public function update(User $user, JobApplication $jobApplication): bool
    {
        return $user->can('edit_job_applications');
    }

    public function delete(User $user, JobApplication $jobApplication): bool
    {
        return $user->can('delete_job_applications');
    }
}
