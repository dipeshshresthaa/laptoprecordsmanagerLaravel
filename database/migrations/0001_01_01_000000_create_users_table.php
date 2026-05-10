<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            // Using UUID to match the C# Guid.NewGuid().ToString() logic
            $table->uuid('id')->primary();

            $table->string('username')->unique();
            $table->string('password_hash');
            $table->string('salt');

            $table->boolean('is_admin')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('requires_password_change')->default(true); // <-- ADD THIS LINE

            // Storing the Employee ID
            // Note: We don't use strict foreign key constraints here to avoid a circular 
            // dependency crash during the initial migration (since the employees table is created after this one).
            $table->string('employee_id')->nullable();

            // Auditable Entity properties mapping
            $table->string('created_by_id')->nullable();
            $table->string('modified_by_id')->nullable();

            // Required for Laravel's "Remember Me" checkbox to function
            $table->rememberToken();
            $table->timestamps();
        });

        // The default migration file also creates standard Laravel cache/job tables. 
        // We leave these intact.
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
