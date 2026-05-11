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
        Schema::create('laptop_upgrades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laptop_id')->constrained('laptops')->onDelete('cascade');
            
            $table->string('upgrade_type'); // e.g., 'RAM', 'Storage', 'Battery'
            $table->string('previous_spec')->nullable(); // e.g., '8GB DDR4'
            $table->string('new_spec')->nullable();      // e.g., '16GB DDR4'
            
            $table->date('upgrade_date');
            $table->decimal('cost', 10, 2)->nullable();
            $table->text('notes')->nullable();
            
            $table->uuid('performed_by_id')->nullable(); // Who logged the upgrade
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laptop_upgrades');
    }
};
