<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subject_teachers', function (Blueprint $table) {
            // Add batch_id column after class_id
            $table->unsignedBigInteger('batch_id')->after('class_id');
            $table->foreign('batch_id')->references('id')->on('batches')->onDelete('cascade');

            // Add semester_id column after batch_id
            $table->unsignedBigInteger('semester_id')->after('batch_id');
            $table->foreign('semester_id')->references('id')->on('semesters')->onDelete('cascade');
        });
        Schema::table('exam_allocations', function (Blueprint $table) {
            // Add batch_id column after class_id
            $table->unsignedBigInteger('batch_id')->after('class_id');
            $table->foreign('batch_id')->references('id')->on('batches')->onDelete('cascade');

            // Add semester_id column after batch_id
            $table->unsignedBigInteger('semester_id')->after('batch_id');
            $table->foreign('semester_id')->references('id')->on('semesters')->onDelete('cascade');
        });
        Schema::table('exam_subject_results', function (Blueprint $table) {
            // Add batch_id column after class_id
            $table->unsignedBigInteger('batch_id')->after('class_id');
            $table->foreign('batch_id')->references('id')->on('batches')->onDelete('cascade');

            // Add semester_id column after batch_id
            $table->unsignedBigInteger('semester_id')->after('batch_id');
            $table->foreign('semester_id')->references('id')->on('semesters')->onDelete('cascade');
        });
        Schema::table('exam_date_sheets', function (Blueprint $table) {
            // Add batch_id column after class_id
            $table->unsignedBigInteger('batch_id')->after('class_id');
            $table->foreign('batch_id')->references('id')->on('batches')->onDelete('cascade');

            // Add semester_id column after batch_id
            $table->unsignedBigInteger('semester_id')->after('batch_id');
            $table->foreign('semester_id')->references('id')->on('semesters')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        
    }
};