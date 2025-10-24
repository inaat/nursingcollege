<?php

namespace App\Models;

use App\Services\CachingService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class Announcement extends Model {
    protected $fillable = [
        'title',
        'description',
        'table_type',
        'table_id',
        'session_id',
        'school_id',
    ];
    public function table()
    {
        return $this->morphTo();
    }
    public function file() {
        return $this->morphMany(File::class, 'modal');
    }

  
    protected static function boot() {
        parent::boot();
        static::deleting(static function ($announcement) { // before delete() method call this
            if ($announcement->file) {
                foreach ($announcement->file as $file) {
                    if (Storage::disk('public')->exists($file->getRawOriginal('file_url'))) {
                        Storage::disk('public')->delete($file->getRawOriginal('file_url'));
                    }
                }

                $announcement->file()->delete();
            }
        });
    }

    public function announcement_class() {
        return $this->hasMany(AnnouncementClass::class);
    }

    public function scopeSubjectTeacher($query) {
        $user = Auth::user();
        if ($user->hasRole('Teacher')) {
            $teacherId = Auth::user()->id;
            return $query->whereHas('announcement_class.subject_teacher', function ($query) use ($teacherId) {
                $query->where('teacher_id', $teacherId)
                    ->whereColumn('class_section_id', 'announcement_classes.class_section_id');
            })->orWhereHas('announcement_class',function($q) {
                $q->where('class_subject_id',null);
            });
            return $query;
        }
        return $query;
    }
}
