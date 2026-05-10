<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_lookups', function (Blueprint $table) {
            $table->id();
            $table->string('category'); // e.g., 'Brand', 'Model', 'Processor'
            $table->string('value');    // e.g., 'Dell', 'Latitude 5420', 'Intel Core i7'
            
            // For cascading dropdowns (e.g., A 'Model' belongs to a 'Brand')
            $table->unsignedBigInteger('parent_id')->nullable();
            
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            
            $table->timestamps();

            // Optional: Foreign key constraint referencing itself
            $table->foreign('parent_id')->references('id')->on('system_lookups')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_lookups');
    }
};