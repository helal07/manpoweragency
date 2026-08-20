<?php

namespace App\Policies;

use App\Models\Client;
use App\Models\User;

class ClientPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view_clients') || $user->can('manage_website_content');
    }

    public function view(User $user, Client $client): bool
    {
        return $user->can('view_clients') || $user->can('manage_website_content');
    }

    public function create(User $user): bool
    {
        return $user->can('create_clients') || $user->can('manage_website_content');
    }

    public function update(User $user, Client $client): bool
    {
        return $user->can('edit_clients') || $user->can('manage_website_content');
    }

    public function delete(User $user, Client $client): bool
    {
        return $user->can('delete_clients') || $user->can('manage_website_content');
    }
}
