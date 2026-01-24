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
            $table->enum('role', ['job_seeker', 'employer', 'admin'])->default('job_seeker')->after('email');
            // Add a new column called 'role' to the users table.
            // It can only be 'job_seeker', 'employer', or 'admin'. If not specified, default to 'job_seeker'.
            // Put it after the email column.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
