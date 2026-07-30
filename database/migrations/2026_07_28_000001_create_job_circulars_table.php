<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_circulars', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('country');
            $table->string('category'); // e.g., Security, Construction, Hospitality, Technical
            $table->integer('vacancy')->default(1);
            $table->string('salary_range');
            $table->date('deadline')->nullable();
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->text('description')->nullable();
            $table->text('requirements')->nullable();
            $table->timestamp('posted_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_circulars');
    }
};
