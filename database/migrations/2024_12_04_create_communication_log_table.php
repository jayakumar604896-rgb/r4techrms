<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('communication_logs', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable(); 
            $table->string('to_email')->nullable();
            $table->string('subject')->nullable();
            $table->text('content')->nullable();

            $table->json('attachments')->nullable();

            $table->string('created_by')->nullable();

            $table->timestamps();

        });

    }

};
