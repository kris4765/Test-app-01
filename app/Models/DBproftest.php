<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DBproftest extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'd_bproftests';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'type',
        'status',
        'user_id',
        'starts_at',
        'ends_at',
        'metadata',
        'content',
        'view_count',
        'rating',
        'reference_id',
    ];

    protected $casts = [
        'status' => 'boolean',
        'metadata' => 'array',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'rating' => 'float',
    ];

    /**
     * Optional relationship: Each test belongs to a user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
