<?php

namespace App\Policies;

use App\Models\Applicant;
use App\Models\User;

class ApplicantPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view_applicants');
    }

    public function view(User $user, Applicant $applicant): bool
    {
        return $user->can('view_applicants');
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Applicant $applicant): bool
    {
        return $user->can('edit_applicants');
    }

    public function delete(User $user, Applicant $applicant): bool
    {
        return $user->can('delete_applicants');
    }
}
