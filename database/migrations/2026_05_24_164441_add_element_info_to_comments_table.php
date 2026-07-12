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
        Schema::table('comments', function (Blueprint $table) {
            $table->text('element_selector')->nullable();
            $table->text('element_xpath')->nullable();
            $table->decimal('offset_x', 8, 5)->nullable();
            $table->decimal('offset_y', 8, 5)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropColumn(['element_selector', 'element_xpath', 'offset_x', 'offset_y']);
        });
    }
};
