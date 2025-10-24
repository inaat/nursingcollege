<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSemesterIdAndBatchIdToFeeTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fee_transactions', function (Blueprint $table) {
            // Use bigInteger instead of integer to match the id columns in semesters and batches tables
            $table->unsignedBigInteger('semester_id')->nullable()->after('class_section_id');
            $table->unsignedBigInteger('batch_id')->nullable()->after('semester_id');
            
            // Add indexes for better performance
            $table->index('semester_id');
            $table->index('batch_id');
            
            // Add foreign key constraints
            // Make sure the referenced tables (semesters, batches) use bigInteger for their id columns
            $table->foreign('semester_id')->references('id')->on('semesters')->onDelete('set null');
            $table->foreign('batch_id')->references('id')->on('batches')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fee_transactions', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign(['semester_id']);
            $table->dropForeign(['batch_id']);
            
            // Drop indexes
            $table->dropIndex(['semester_id']);
            $table->dropIndex(['batch_id']);
            
            // Drop columns
            $table->dropColumn(['semester_id', 'batch_id']);
        });
    }
}