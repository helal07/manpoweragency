<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class UpdateAdminEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:update-email {old_email? : The current admin email} {new_email? : The new admin email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change or update an existing admin email address';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $oldEmail = $this->argument('old_email') ?: $this->ask('Enter CURRENT admin email', 'admin@admin.com');

        $user = User::where('email', $oldEmail)->first();

        if (! $user) {
            $this->error("❌ No admin user found with email [{$oldEmail}].");
            
            // List available admin emails to help the user
            $admins = User::pluck('email')->toArray();
            if (! empty($admins)) {
                $this->info("Available admin accounts in database:");
                foreach ($admins as $email) {
                    $this->line(" - {$email}");
                }
            }
            return self::FAILURE;
        }

        $newEmail = $this->argument('new_email') ?: $this->ask('Enter NEW admin email');

        if (empty($newEmail) || ! filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
            $this->error('❌ Please provide a valid email address.');
            return self::FAILURE;
        }

        // Check if new email is already taken
        if (User::where('email', $newEmail)->where('id', '!=', $user->id)->exists()) {
            $this->error("❌ An account with email [{$newEmail}] already exists.");
            return self::FAILURE;
        }

        $user->email = $newEmail;
        $user->save();

        $this->info("✅ Admin email successfully updated from [{$oldEmail}] to [{$newEmail}]!");

        return self::SUCCESS;
    }
}
