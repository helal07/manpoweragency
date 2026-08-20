<?php

namespace App\Policies;

use App\Models\JobCircular;
use App\Models\User;

class JobCircularPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view_job_circulars');
    }

    public function view(User $user, JobCircular $jobCircular): bool
    {
        return $user->can('view_job_circulars');
    }

    public function create(User $user): bool
    {
        return $user->can('create_job_circulars');
    }

    public function update(User $user, JobCircular $jobCircular): bool
    {
        return $user->can('edit_job_circulars');
    }

    public function delete(User $user, JobCircular $jobCircular): bool
    {
        return $user->can('delete_job_circulars');
    }
}
