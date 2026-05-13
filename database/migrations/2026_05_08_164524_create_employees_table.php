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
        Schema::create('employees', function (Blueprint $table) {
            // Use string(8) as primary key to match your C# requirements
            $table->string('id', 8)->primary();
            $table->string('emp_code')->unique();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');

            $table->string('phone_number')->nullable();
            $table->string('address_state')->nullable();
            $table->string('address_district')->nullable();
            $table->string('address_municipality')->nullable();
            $table->string('pan_number')->nullable();

            // Role and Designation
            $table->string('role')->default('Other');
            $table->string('designation')->nullable();

            // Dates
            $table->date('joining_date')->nullable();
            $table->date('exit_date')->nullable();
            $table->string('exit_reason')->nullable();
            $table->date('articleship_completion_date')->nullable();

            // Financial Info
            $table->string('bank_name')->nullable();
            $table->string('bank_branch')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('cit_number')->nullable();

            $table->boolean('is_active')->default(true);

            // Document Paths
            $table->string('articleship_deed_path')->nullable();
            $table->string('completion_certificate_path')->nullable();

            // Define the column first.
            // IMPORTANT: Length must match the primary key (8)
            $table->string('principal_id', 8)->nullable();

            // Foreign key for User Account (Standard BigInt)
            $table->foreignUuid('user_id')->nullable()->constrained('users')->nullOnDelete();

            // Audit Properties
            $table->uuid('created_by_id')->nullable();
            $table->uuid('modified_by_id')->nullable();
            $table->timestamps();
        });

        // Add self-referencing foreign key in a separate block for PostgreSQL compatibility
        Schema::table('employees', function (Blueprint $table) {
            $table->foreign('principal_id')
                ->references('id')
                ->on('employees')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
