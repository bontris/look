<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Path extends Model
{
    const CREATED_AT = 'made';

    const UPDATED_AT = 'mark';
    
    protected $casts = [
        'made' => 'datetime',
        'mark' => 'datetime',
        'wipe' => 'datetime'
    ];

    protected $fillable = [
        'pass',
        'rank',
        'name',
        'pick'
    ];

    protected $table = 'paths';

    protected $primaryKey = 'row';

    public function list () {
    	return $this->hasMany(Stop::class, 'bind', 'row');
    }
}