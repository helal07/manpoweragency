<?php

namespace App\Policies;

use App\Models\Leader;
use App\Models\User;

class LeaderPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view_leaders') || $user->can('manage_website_content');
    }

    public function view(User $user, Leader $leader): bool
    {
        return $user->can('view_leaders') || $user->can('manage_website_content');
    }

    public function create(User $user): bool
    {
        return $user->can('create_leaders') || $user->can('manage_website_content');
    }

    public function update(User $user, Leader $leader): bool
    {
        return $user->can('edit_leaders') || $user->can('manage_website_content');
    }

    public function delete(User $user, Leader $leader): bool
    {
        return $user->can('delete_leaders') || $user->can('manage_website_content');
    }
}
