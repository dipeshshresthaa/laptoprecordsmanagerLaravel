<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('laptops', function (Blueprint $table) {
            // Safely add columns if they don't already exist
            if (! Schema::hasColumn('laptops', 'disposal_date')) {
                $table->date('disposal_date')->nullable();
            }
            $table->string('disposal_method')->nullable(); // e.g., Scrapped, Sold, Lost
            $table->text('disposal_reason')->nullable();
            $table->foreignUuid('disposed_by_id')->nullable()->constrained('users');
        });
    }

    public function down(): void
    {
        Schema::table('laptops', function (Blueprint $table) {
            $table->dropForeign(['disposed_by_id']);
            $table->dropColumn(['disposal_date', 'disposal_method', 'disposal_reason', 'disposed_by_id']);
        });
    }
};
