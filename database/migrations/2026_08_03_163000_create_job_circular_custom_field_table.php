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
        Schema::create('job_circular_custom_field', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_circular_id')->constrained('job_circulars')->cascadeOnDelete();
            $table->foreignId('custom_field_id')->constrained('custom_fields')->cascadeOnDelete();
            $table->boolean('is_required')->default(false);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['job_circular_id', 'custom_field_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_circular_custom_field');
    }
};
