<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
  
  /**
     * Return list of ClassLevels for a business
     *
     * @param boolean $show_none = false
     *
     * @return array
     */
    public static function forDropdown($status = 1, $show_none = false)
{
    $query = Semester::query(); // Correct model reference

    if ($status) {
        $query->where('status', $status);
    }

    $semesters = $query->pluck('title', 'id'); // Fix variable name

    if ($show_none) {
        $semesters->prepend(__('lang.none'), '');
    }

    return $semesters;
}
}
