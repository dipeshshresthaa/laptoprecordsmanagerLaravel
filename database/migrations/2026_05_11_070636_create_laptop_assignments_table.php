<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laptop_assignments', function (Blueprint $table) {
            $table->id();

            // 1. Laptop ID (Assumed standard auto-incrementing bigint)
            $table->foreignId('laptop_id')->constrained('laptops')->onDelete('cascade');

            // 2. Employee ID (Must be string(8) to match employees.id)
            $table->string('employee_id', 8);
            $table->foreign('employee_id')
                ->references('id')
                ->on('employees')
                ->onDelete('cascade');

            $table->date('assigned_date');
            $table->date('returned_date')->nullable();

            $table->string('return_condition')->nullable();
            $table->text('return_reason')->nullable();

            // 3. Audit IDs (Must be foreignUuid to match users.id)
            $table->foreignUuid('assigned_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignUuid('returned_by_id')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laptop_assignments');
    }
};
