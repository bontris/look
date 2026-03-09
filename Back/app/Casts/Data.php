<?php

namespace App\Casts;

use Illuminate\Support\Facades\DB;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class Data implements CastsAttributes
{
    public function get ($model, $key, $value, $attributes)
    {
        return json_decode($value);
    }

    public function set ($model, $key, $value, $attributes)
    {
        return json_encode($value);
    }
}