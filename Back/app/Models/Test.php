<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    const CREATED_AT = 'made';

    const UPDATED_AT = 'mark';
    
    protected $casts = [
        'made' => 'datetime',
        'mark' => 'datetime',
        'wipe' => 'datetime'
    ];

    protected $fillable = [
        'bind',
        'code'
    ];

    protected $table = 'tests';

    protected $primaryKey = 'row';

    public function firm () {
    	return $this->hasOne(Firm::class, 'row', 'bind');
    }

    public function list () {
    	return $this->hasMany(Pick::class, 'bind', 'row');
    }
}