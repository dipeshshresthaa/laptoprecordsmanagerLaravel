<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('laptop_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laptop_id')->constrained('laptops')->onDelete('cascade');
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            
            $table->date('assigned_date');
            $table->date('returned_date')->nullable();
            
            $table->string('return_condition')->nullable();
            $table->text('return_reason')->nullable();
            
            // Audit trailing
            $table->foreignId('assigned_by_id')->nullable()->constrained('users');
            $table->foreignId('returned_by_id')->nullable()->constrained('users');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laptop_assignments');
    }
};