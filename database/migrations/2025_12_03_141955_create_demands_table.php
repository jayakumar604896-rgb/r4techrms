<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('demands', function (Blueprint $table) {
            $table->id();
            
            $table->enum('user_type', ['Domestic', 'International']);
            $table->string('demand_ref_no')->nullable();
            $table->date('demand_open_date')->nullable();
            $table->date('demand_closed_date')->nullable();
            $table->string('recruiter_name')->nullable();
            $table->string('designation_offered')->nullable();
            $table->string('client_agent_source')->nullable();
            $table->integer('no_of_positions')->nullable();
            $table->string('qualification')->nullable();
            $table->string('experience')->nullable();
            $table->string('job_location')->nullable();
            $table->string('industry')->nullable();
            $table->string('candidate_location')->nullable();
            $table->string('created_by')->nullable();
            
            $table->string('mode_of_interview')->nullable();
            $table->json('mode_of_selection')->nullable();
            $table->date('interview_date')->nullable();
            $table->string('interview_location')->nullable();
            $table->json('type_of_hiring')->nullable();
            
            // Advertisement & Media
            $table->integer('required_cvs')->nullable();
            $table->json('advertisement_portals')->nullable();
            $table->text('other_information')->nullable();
            
            $table->enum('status', ['Open', 'Closed', 'In Progress'])->default('Open');
            $table->timestamps();
            
        });
    }

    public function down()
    {
        Schema::dropIfExists('demands');
    }
};