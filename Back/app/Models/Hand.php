<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hand extends Model
{
    protected $casts = [
        'made' => 'datetime',
        'mark' => 'datetime',
        'wipe' => 'datetime'
    ];

    protected $table = 'hands';

    protected $fillable = ['type', 'card', 'last', 'name', 'mail', 'icon', 'tone', 'data'];
}