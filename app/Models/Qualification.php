<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Qualification extends Model
{
    use HasFactory;

    protected $table = 'qualifications';

    protected $fillable = [
        'student_id',
        'qualification',
        'passing_year',
        'marks_obtained',
        'total_marks',
        'percentage',
        'biology_marks',
        'board',
    ];

    // Relationship: A qualification belongs to a student
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
