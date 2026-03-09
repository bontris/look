<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pick extends Model
{
    const CREATED_AT = 'made';

    const UPDATED_AT = 'mark';

    protected $fillable = [
        'data',
        'risk',
        'note',
        'rate',
        'plan'
    ];

    protected $table = 'picks';

    protected $primaryKey = 'row';

    public function test () {
    	return $this->belongsTo(Test::class, 'bind', 'row');
    }
}