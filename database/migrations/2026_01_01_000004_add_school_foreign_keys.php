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
        // Add foreign key constraints for school_id columns that were created without constraints
        Schema::table('qcm_papers', function (Blueprint $table) {
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('set null');
        });

        Schema::table('qcm_exams', function (Blueprint $table) {
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('qcm_papers', function (Blueprint $table) {
            $table->dropForeign(['school_id']);
        });

        Schema::table('qcm_exams', function (Blueprint $table) {
            $table->dropForeign(['school_id']);
        });
    }
};