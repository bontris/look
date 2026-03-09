<?php

namespace App\Models;

use App\Casts\Spot;

use Illuminate\Database\Eloquent\Model;

class Gift extends Model
{
    const CREATED_AT = 'made';

    const UPDATED_AT = 'mark';

    protected $casts = [
        'made' => 'datetime',
        'mark' => 'datetime',
        'wipe' => 'datetime',
        'spot' => Spot::class
    ];

    protected $fillable = [
        'done',
        'note',
        'date',
        'spot'
    ];

    protected $table = 'donations';

    protected $primaryKey = 'row';
}