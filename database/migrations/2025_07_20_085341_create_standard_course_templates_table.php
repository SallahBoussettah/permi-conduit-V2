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
        Schema::create('standard_course_templates', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('permit_category_id')->constrained();
            $table->integer('sequence_order')->default(0);
            $table->string('exam_section'); // IE, Socle1, Theme, IO, Socle2, Manoeuvres
            $table->json('default_materials')->nullable(); // Optional default materials structure
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('standard_course_templates');
    }
};
