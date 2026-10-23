<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // database/migrations/2024_01_01_create_recruiter_daily_progress_table.php
        Schema::create('recruiter_monthly_summary', function (Blueprint $table) {
            $table->id();
            $table->string('month')->nullable();
            $table->string('hr_staff')->nullable();
            $table->json('totals')->nullable();
            $table->json('unit_progress')->nullable();
            $table->json('overall_progress')->nullable();
            $table->decimal('overall_total', 5, 2)->default(0);
            $table->timestamps();
            
            $table->unique(['month', 'hr_staff']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('daily_progress');
    }
};
