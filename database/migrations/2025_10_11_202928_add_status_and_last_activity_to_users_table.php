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
       Schema::table('users', function (Blueprint $table) {
            $table->timestamp('last_activity')->nullable();
            $table->timestamp('user_type')->nullable();
            $table->enum('user_status', ['active', 'idle', 'offline'])->default('active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['last_activity', 'user_type', 'user_status']);
        });
    }
};
