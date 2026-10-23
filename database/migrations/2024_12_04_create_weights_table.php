// database/migrations/2024_12_04_create_weights_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('weights', function (Blueprint $table) {
            $table->id();
            $table->string('metric_name')->nullable();
            $table->string('excel_column');
            $table->decimal('weight', 5, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        
        // Insert default weights (from your Excel)
        DB::table('weights')->insert([
            ['metric_name' => 'Business Profile Submission', 'excel_column' => 'busi_profile_submission', 'weight' => 0.02],
            ['metric_name' => 'Tele Marketing', 'excel_column' => 'tele_marketing', 'weight' => 0.04],
            ['metric_name' => 'Client Empanelment', 'excel_column' => 'client_empanelment', 'weight' => 0.06],
            ['metric_name' => 'CV Submitted', 'excel_column' => 'cv_submitted', 'weight' => 0.15],
            ['metric_name' => 'Interview Organized', 'excel_column' => 'interview_organized', 'weight' => 0.15],
            ['metric_name' => 'Interview Attended', 'excel_column' => 'interview_attended', 'weight' => 0.15],
            ['metric_name' => 'Selected', 'excel_column' => 'selected', 'weight' => 0.25],
            ['metric_name' => 'Job Reported', 'excel_column' => 'job_reported', 'weight' => 0.15],
            ['metric_name' => 'Invoiced', 'excel_column' => 'invoiced', 'weight' => 0.12],
            ['metric_name' => 'Payment Received', 'excel_column' => 'payment_received', 'weight' => 0.06],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('weights');
    }
};