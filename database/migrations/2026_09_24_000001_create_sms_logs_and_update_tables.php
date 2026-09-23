<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create sms_logs table
        Schema::create('sms_logs', function (Blueprint $table) {
            $table->id();
            $table->string('recipient');
            $table->text('message');
            $table->string('type')->default('general'); // otp, interview_card, test, general
            $table->string('status')->default('sent'); // sent, failed, simulated
            $table->text('gateway_response')->nullable();
            $table->foreignId('applicant_id')->nullable()->constrained('applicants')->nullOnDelete();
            $table->foreignId('job_application_id')->nullable()->constrained('job_applications')->nullOnDelete();
            $table->timestamps();
        });

        // 2. Add OTP and verification fields to applicants table if they don't exist
        Schema::table('applicants', function (Blueprint $table) {
            if (!Schema::hasColumn('applicants', 'phone_verified_at')) {
                $table->timestamp('phone_verified_at')->nullable()->after('email_verified_at');
            }
            if (!Schema::hasColumn('applicants', 'otp_code')) {
                $table->string('otp_code', 10)->nullable()->after('phone_verified_at');
            }
            if (!Schema::hasColumn('applicants', 'otp_expires_at')) {
                $table->timestamp('otp_expires_at')->nullable()->after('otp_code');
            }
        });

        // 3. Add interview details & admit card fields to job_applications table
        Schema::table('job_applications', function (Blueprint $table) {
            if (!Schema::hasColumn('job_applications', 'interview_date')) {
                $table->date('interview_date')->nullable()->after('notes');
            }
            if (!Schema::hasColumn('job_applications', 'interview_time')) {
                $table->string('interview_time')->nullable()->after('interview_date');
            }
            if (!Schema::hasColumn('job_applications', 'interview_venue')) {
                $table->string('interview_venue')->nullable()->after('interview_time');
            }
            if (!Schema::hasColumn('job_applications', 'interview_instructions')) {
                $table->text('interview_instructions')->nullable()->after('interview_venue');
            }
            if (!Schema::hasColumn('job_applications', 'interview_called_at')) {
                $table->timestamp('interview_called_at')->nullable()->after('interview_instructions');
            }
            if (!Schema::hasColumn('job_applications', 'admit_card_token')) {
                $table->string('admit_card_token', 64)->nullable()->unique()->after('interview_called_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropColumn([
                'interview_date',
                'interview_time',
                'interview_venue',
                'interview_instructions',
                'interview_called_at',
                'admit_card_token',
            ]);
        });

        Schema::table('applicants', function (Blueprint $table) {
            $table->dropColumn([
                'phone_verified_at',
                'otp_code',
                'otp_expires_at',
            ]);
        });

        Schema::dropIfExists('sms_logs');
    }
};
