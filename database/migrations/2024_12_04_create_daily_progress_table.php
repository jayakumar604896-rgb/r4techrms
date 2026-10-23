<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('daily_progress', function (Blueprint $table) {
            $table->id();

            // Make sure user_id type matches users.id -> BIGINT UNSIGNED
            $table->string('user_id')->nullable();

            $table->date('date')->nullable();
            $table->string('client_name')->nullable();

            // All Excel columns
            $table->integer('busi_profile_submission')->default(0);
            $table->integer('tele_marketing')->default(0);
            $table->integer('client_empanelment')->default(0);
            $table->integer('cv_submitted')->default(0);
            $table->integer('interview_organized')->default(0);
            $table->integer('interview_attended')->default(0);
            $table->integer('rejected')->default(0);
            $table->integer('selected')->default(0);
            $table->integer('job_reported')->default(0);
            $table->integer('invoiced')->default(0);
            $table->integer('payment_received')->default(0);

            // Calculated fields
            $table->decimal('selection_percentage', 5, 2)->nullable();
            $table->decimal('rejection_percentage', 5, 2)->nullable();
            $table->text('remarks')->nullable();

            $table->timestamps();

            // Prevent duplicate entries for same user on same date
            $table->unique(['user_id', 'date']);

            
        });
    }

    public function down()
    {
        Schema::dropIfExists('daily_progress');
    }
};
