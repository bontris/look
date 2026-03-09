<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Firm extends Model
{
    protected $casts = [
        'creation' => 'datetime',
        'deletion' => 'datetime'
    ];

    protected $table = 'firms';

    protected $hidden = ['pass'];

    protected $fillable = ['type', 'page', 'name', 'mail', 'face', 'tone', 'data'];
}