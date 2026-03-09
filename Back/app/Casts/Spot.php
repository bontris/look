<?php

namespace App\Casts;

use Illuminate\Support\Facades\DB;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class Spot implements CastsAttributes
{
    public function get ($model, $key, $value, $attributes)
    {
        return $value ? [($data = unpack('x4/corder/Ltype/dlng/dlat', $value))['lng'], $data['lat']] : null;
    }

    public function set ($model, $key, $value, $attributes)
    {
        return DB::raw(sprintf('POINT(%f, %f)', $value[0], $value[1]));
    }
}