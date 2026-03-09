<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $casts = [
        'made' => 'datetime',
        'mark' => 'datetime',
        'wipe' => 'datetime'
    ];

    protected $table = 'leads';

    protected $fillable = ['type', 'page', 'card', 'last', 'name', 'mail', 'icon', 'tone', 'data'];

    public function firm () {
    	return $this->hasOne(Firm::class, 'row', 'bind');
    }
}