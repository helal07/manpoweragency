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
        Schema::create('job_application_field_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_application_id')->constrained('job_applications')->cascadeOnDelete();
            $table->foreignId('custom_field_id')->constrained('custom_fields')->cascadeOnDelete();
            $table->text('value')->nullable();
            $table->timestamps();

            $table->unique(['job_application_id', 'custom_field_id'], 'app_field_val_app_field_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_application_field_values');
    }
};
