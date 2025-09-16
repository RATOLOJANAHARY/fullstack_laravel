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
        Schema::table('users_phone_number', function (Blueprint $table) {
            $table->dropColumn(['to', 'message', 'status']);
            $table->string('phone_number')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users_phone_number', function (Blueprint $table) {
            $table->dropColumn('phone_number');
            $table->string('to');
            $table->string('message');
            $table->boolean('status')->default(0);
        });
    }
};
