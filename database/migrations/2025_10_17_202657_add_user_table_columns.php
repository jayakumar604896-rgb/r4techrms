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
            $table->string('company_serial_number')->nullable();
            $table->string('photo')->nullable();
            $table->string('staff_contact_no')->nullable();
            $table->string('designation')->nullable();
            $table->string('alternate_contact_no')->nullable();
            $table->string('address')->nullable();
            $table->string('aadhar_card_number')->nullable();
            $table->date('date_of_joining')->nullable();
            $table->date('dob')->nullable();
            $table->string('field1')->nullable();
            $table->string('field2')->nullable();
            $table->string('field3')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn([
            'company_serial_number',
            'designation',
            'photo',
            'staff_contact_no',
            'alternate_contact_no',
            'address',
            'aadhar_card_number',
            'date_of_joining',
            'dob',
            'field1',
            'field2',
            'field3',
        ]);
    });
}
};
