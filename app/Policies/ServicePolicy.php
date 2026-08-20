<?php

namespace App\Policies;

use App\Models\Service;
use App\Models\User;

class ServicePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view_services') || $user->can('manage_website_content');
    }

    public function view(User $user, Service $service): bool
    {
        return $user->can('view_services') || $user->can('manage_website_content');
    }

    public function create(User $user): bool
    {
        return $user->can('create_services') || $user->can('manage_website_content');
    }

    public function update(User $user, Service $service): bool
    {
        return $user->can('edit_services') || $user->can('manage_website_content');
    }

    public function delete(User $user, Service $service): bool
    {
        return $user->can('delete_services') || $user->can('manage_website_content');
    }
}
