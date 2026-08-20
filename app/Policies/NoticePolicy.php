<?php

namespace App\Policies;

use App\Models\Notice;
use App\Models\User;

class NoticePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view_notices');
    }

    public function view(User $user, Notice $notice): bool
    {
        return $user->can('view_notices');
    }

    public function create(User $user): bool
    {
        return $user->can('create_notices');
    }

    public function update(User $user, Notice $notice): bool
    {
        return $user->can('edit_notices');
    }

    public function delete(User $user, Notice $notice): bool
    {
        return $user->can('delete_notices');
    }
}
