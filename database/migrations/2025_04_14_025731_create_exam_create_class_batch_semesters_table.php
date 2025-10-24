<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_create_class_batch_semesters', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('exam_create_id');
            $table->foreign('exam_create_id')->references('id')->on('exam_creates')->onDelete('cascade');
            $table->unsignedInteger('class_id');
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');
            $table->unsignedBigInteger('batch_id');
            $table->foreign('batch_id')->references('id')->on('batches')->onDelete('cascade');
            $table->unsignedBigInteger('semester_id');
            $table->foreign('semester_id')->references('id')->on('semesters')->onDelete('cascade');
            $table->unsignedInteger('class_section_id');
            $table->foreign('class_section_id')->references('id')->on('class_sections')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_create_class_batch_semesters');
    }
};