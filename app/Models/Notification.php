<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

     protected $fillable = [
        'send_from',
        'send_to',
        'data',
        'is_read',
    ];
    

    protected $casts = [
        'is_read' => 'boolean',
    ];
}
