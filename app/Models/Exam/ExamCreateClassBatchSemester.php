<?php

namespace App\Models\Exam;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamCreateClassBatchSemester extends Model
{
    protected $table = 'exam_create_class_batch_semesters';


    protected $guarded = ['id'];

    /**
     * Get the exam create that owns this record.
     */
    public function examCreate(): BelongsTo
    {
        return $this->belongsTo(ExamCreate::class);
    }

    /**
     * Get the class that owns this record.
     */
    public function class(): BelongsTo
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    /**
     * Get the class section that owns this record.
     */
    public function classSection(): BelongsTo
    {
        return $this->belongsTo(ClassSection::class);
    }

    /**
     * Get the batch that owns this record.
     */
    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    /**
     * Get the semester that owns this record.
     */
    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }
}