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
            $table->boolean('is_auto_seeded')->default(false)->after('school_id');
            $table->string('exam_section')->nullable()->after('is_auto_seeded');
            $table->integer('sequence_order')->default(0)->after('exam_section');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('is_auto_seeded');
            $table->dropColumn('exam_section');
            $table->dropColumn('sequence_order');
        });
    }
};
