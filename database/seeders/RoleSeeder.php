<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'admin']);
        $editor = Role::firstOrCreate(['name' => 'editor', 'guard_name' => 'admin']);

        $adminUsers = User::all();
        foreach ($adminUsers as $user) {
            $user->assignRole($superAdmin);
        }
    }
}
