<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laptop_repairs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laptop_id')->constrained('laptops')->onDelete('cascade');

            // Send details
            $table->string('vendor_name');
            $table->text('issue_description');
            $table->date('sent_date');

            // Return details
            $table->date('returned_date')->nullable();
            $table->decimal('repair_cost', 10, 2)->nullable();
            $table->text('repair_notes')->nullable();

            // Audit trailing - Fixed for UUID compatibility
            $table->foreignUuid('sent_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignUuid('returned_by_id')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laptop_repairs');
    }
};
