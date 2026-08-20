<?php

namespace App\Policies;

use App\Models\HeroBanner;
use App\Models\User;

class HeroBannerPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view_hero_banners') || $user->can('manage_website_content');
    }

    public function view(User $user, HeroBanner $heroBanner): bool
    {
        return $user->can('view_hero_banners') || $user->can('manage_website_content');
    }

    public function create(User $user): bool
    {
        return $user->can('create_hero_banners') || $user->can('manage_website_content');
    }

    public function update(User $user, HeroBanner $heroBanner): bool
    {
        return $user->can('edit_hero_banners') || $user->can('manage_website_content');
    }

    public function delete(User $user, HeroBanner $heroBanner): bool
    {
        return $user->can('delete_hero_banners') || $user->can('manage_website_content');
    }
}
