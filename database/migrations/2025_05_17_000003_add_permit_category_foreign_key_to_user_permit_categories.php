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
        Schema::table('user_permit_categories', function (Blueprint $table) {
            $table->foreign('permit_category_id')->references('id')->on('permit_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_permit_categories', function (Blueprint $table) {
            $table->dropForeign(['permit_category_id']);
        });
    }
};