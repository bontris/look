<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Firm extends Model
{
    protected $casts = [
        'made' => 'datetime',
        'mark' => 'datetime',
        'wipe' => 'datetime'
    ];

    protected $table = 'firms';

    protected $fillable = ['type', 'page', 'card', 'name', 'mail', 'icon', 'tone', 'data'];

    public function hand () {
    	return $this->hasOne(Hand::class, 'row', 'lead');
    }
}