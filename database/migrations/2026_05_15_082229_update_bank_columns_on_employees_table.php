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
        Schema::table('employees', function (Blueprint $table) {
            // Drop old string columns
            $table->dropColumn(['bank_name', 'bank_branch']);
        });

        Schema::table('employees', function (Blueprint $table) {
            // Add new foreign key columns
            $table->unsignedBigInteger('bank_name_id')->nullable();
            $table->unsignedBigInteger('bank_branch_id')->nullable();

            // Set constraints
            $table->foreign('bank_name_id')->references('id')->on('system_lookups')->nullOnDelete();
            $table->foreign('bank_branch_id')->references('id')->on('system_lookups')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['bank_name_id']);
            $table->dropForeign(['bank_branch_id']);
            $table->dropColumn(['bank_name_id', 'bank_branch_id']);

            $table->string('bank_name')->nullable();
            $table->string('bank_branch')->nullable();
        });
    }
};
