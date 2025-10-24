<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_name',
        'description',
        'selected_columns',
        'column_order',
        'report_type',
        'created_by',
        'is_active'
    ];

    protected $casts = [
        'selected_columns' => 'array',
        'column_order' => 'array',
        'is_active' => 'boolean'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}