<?php

namespace App\Models;

use App\Casts\Spot;

use Illuminate\Database\Eloquent\Model;

class Stop extends Model
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

    protected $table = 'stops';

    protected $primaryKey = 'row';

    public function path () {
    	return $this->belongsTo(Path::class, 'bind', 'row');
    }

    public function gift () {
    	return $this->belongsTo(Gift::class, 'pick', 'row');
    }
}