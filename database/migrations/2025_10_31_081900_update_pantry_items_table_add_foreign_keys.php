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
        Schema::table('pantry_items', function (Blueprint $table) {
            // Drop old indexes
            $table->dropIndex(['user_id', 'category']);

            // Add new columns for foreign keys
            $table->foreignId('category_id')->nullable()->after('name')->constrained()->nullOnDelete();
            $table->foreignId('location_id')->nullable()->after('expiration_date')->constrained()->nullOnDelete();

            // Drop old string columns (after migrating data in a separate step if needed)
            $table->dropColumn(['category', 'location']);

            // Add new index
            $table->index(['user_id', 'category_id']);
            $table->index(['user_id', 'location_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pantry_items', function (Blueprint $table) {
            // Drop foreign key indexes
            $table->dropForeign(['category_id']);
            $table->dropForeign(['location_id']);
            $table->dropIndex(['user_id', 'category_id']);
            $table->dropIndex(['user_id', 'location_id']);

            // Drop foreign key columns
            $table->dropColumn(['category_id', 'location_id']);

            // Restore old string columns
            $table->string('category')->after('name');
            $table->string('location')->after('expiration_date');

            // Restore old index
            $table->index(['user_id', 'category']);
        });
    }
};
