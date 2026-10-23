<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // database/migrations/2024_01_01_create_recruiter_daily_progress_table.php
        Schema::create('recruiter_daily_progress', function (Blueprint $table) {
        $table->id();
        $table->string('month')->nullable();
        $table->string('hr_staff')->nullable();
        $table->integer('entry_day')->nullable();
        $table->string('client_name')->nullable();
        $table->integer('busi_profile')->default(0);
        $table->integer('tele_marketing')->default(0);
        $table->integer('client_empanelment')->default(0);
        $table->integer('cv_submitted')->default(0);
        $table->integer('interview_attended')->default(0);
        $table->integer('rejected')->default(0);
        $table->integer('selected')->default(0);
        $table->integer('job_reported')->default(0);
        $table->integer('invoiced')->default(0);
        $table->integer('payment_received')->default(0);
        $table->text('remarks')->nullable();
        $table->date('entry_date');
        $table->timestamps();
        
        $table->index(['month', 'hr_staff']);
    });
    }

    public function down()
    {
        Schema::dropIfExists('daily_progress');
    }
};
