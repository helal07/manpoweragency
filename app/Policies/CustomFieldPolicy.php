<?php

namespace App\Policies;

use App\Models\CustomField;
use App\Models\User;

class CustomFieldPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('manage_custom_fields');
    }

    public function view(User $user, CustomField $customField): bool
    {
        return $user->can('manage_custom_fields');
    }

    public function create(User $user): bool
    {
        return $user->can('manage_custom_fields');
    }

    public function update(User $user, CustomField $customField): bool
    {
        return $user->can('manage_custom_fields');
    }

    public function delete(User $user, CustomField $customField): bool
    {
        return $user->can('manage_custom_fields');
    }
}
