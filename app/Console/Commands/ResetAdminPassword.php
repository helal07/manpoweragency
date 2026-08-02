<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class ResetAdminPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:reset-password {email? : The admin email address} {password? : The new password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Quickly reset or set an admin user password';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = $this->argument('email') ?: $this->ask('Enter admin email', 'admin@admin.com');

        $user = User::where('email', $email)->first();

        if (! $user) {
            $create = $this->confirm("User with email [{$email}] was not found. Would you like to create it as a new Admin?", true);

            if (! $create) {
                $this->error('Operation cancelled.');
                return self::FAILURE;
            }

            $name = $this->ask('Enter admin name', 'System Admin');
            $password = $this->argument('password') ?: $this->secret('Enter new password');

            if (empty($password)) {
                $this->error('Password cannot be empty.');
                return self::FAILURE;
            }

            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
            ]);

            $this->info("✅ Admin user [{$email}] created successfully!");
            return self::SUCCESS;
        }

        $password = $this->argument('password') ?: $this->secret('Enter new password');

        if (empty($password)) {
            $this->error('Password cannot be empty.');
            return self::FAILURE;
        }

        $user->password = Hash::make($password);
        $user->save();

        $this->info("✅ Password for admin [{$email}] has been successfully reset!");

        return self::SUCCESS;
    }
}
