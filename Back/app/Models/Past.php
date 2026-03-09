<?php

namespace App\Models;

use App\Casts\Data;

use Illuminate\Database\Eloquent\Model;

class Past extends Model
{
    const CREATED_AT = 'date';

    protected $casts = [
        'date' => 'datetime',
        'mark' => 'datetime',
        'data' => Data::class
    ];

    protected $table = 'pasts';

    protected $primaryKey = 'row';
}