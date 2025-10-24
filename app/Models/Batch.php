<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    
    public static function forDropdown($status = 1, $show_none = false)
{
    $query = Batch::query(); // Correct model reference

    if ($status) {
        $query->where('status', $status);
    }

    $batches = $query->pluck('title', 'id'); // Fix variable name

    if ($show_none) {
        $batches->prepend(__('lang.none'), '');
    }

    return $batches;
}
}
