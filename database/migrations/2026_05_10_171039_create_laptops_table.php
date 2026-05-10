<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laptops', function (Blueprint $table) {
            $table->id(); // Matches C# int Id
            
            $table->string('laptop_fa_code')->nullable();
            $table->string('serial_number')->unique();
            $table->string('service_tag')->nullable();

            // Foreign Keys to your SystemLookup table
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->unsignedBigInteger('model_id')->nullable();
            $table->unsignedBigInteger('processor_id')->nullable();
            $table->unsignedBigInteger('ram_size_id')->nullable();
            $table->unsignedBigInteger('storage_size_id')->nullable();
            $table->unsignedBigInteger('screen_size_id')->nullable();

            // Specs
            $table->string('ram_type')->nullable(); // e.g., DDR4
            $table->string('storage_type')->nullable(); // e.g., NVMe

            // Dates & Status
            $table->date('purchase_date');
            $table->date('disposal_date')->nullable();
            $table->text('laptop_identifier_notes')->nullable();
            $table->text('laptop_disposal_notes')->nullable();
            $table->string('status')->default('Available'); 

            // Web apps store file paths, not raw byte arrays in the DB
            $table->string('laptop_photo_path')->nullable();

            // Auditable Entity Columns
            $table->uuid('created_by_id')->nullable();
            $table->uuid('modified_by_id')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laptops');
    }
};