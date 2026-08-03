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
        Schema::table('applicants', function (Blueprint $table) {
            // Personal
            $table->string('fathers_name')->nullable();
            $table->string('mothers_name')->nullable();
            $table->string('mobile_no')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gender')->nullable();           // male, female, other
            $table->string('marital_status')->nullable();    // single, married, divorced, widowed

            // Education
            $table->smallInteger('ssc_year')->unsigned()->nullable();
            $table->string('ssc_result')->nullable();
            $table->smallInteger('hsc_year')->unsigned()->nullable();
            $table->string('hsc_result')->nullable();
            $table->string('highest_education')->nullable();

            // Experience & Skills
            $table->text('experience_details')->nullable();
            $table->unsignedTinyInteger('experience_years')->nullable();
            $table->boolean('can_speak_english')->default(false);
            $table->string('english_proficiency')->nullable(); // basic, conversational, fluent, native
            $table->string('other_languages')->nullable();

            // Travel & Documents
            $table->string('preferred_country')->nullable();
            $table->date('passport_expiry')->nullable();

            // Emergency Contact
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->dropColumn([
                'fathers_name', 'mothers_name', 'mobile_no', 'date_of_birth',
                'gender', 'marital_status',
                'ssc_year', 'ssc_result', 'hsc_year', 'hsc_result', 'highest_education',
                'experience_details', 'experience_years', 'can_speak_english',
                'english_proficiency', 'other_languages',
                'preferred_country', 'passport_expiry',
                'emergency_contact_name', 'emergency_contact_phone',
            ]);
        });
    }
};
