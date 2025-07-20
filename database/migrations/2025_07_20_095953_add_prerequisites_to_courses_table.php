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
        Schema::table('courses', function (Blueprint $table) {
            $table->json('prerequisite_courses')->nullable()->after('sequence_order');
            $table->boolean('requires_sequential_completion')->default(true)->after('prerequisite_courses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('prerequisite_courses');
            $table->dropColumn('requires_sequential_completion');
        });
    }
};
