<?php

namespace App\Helpers;

class AppHelper
{
    public static function rand ($size) {
	    $item = 0;

	    $code = '';

	    $pool = '0123456789';

	    $stop = strlen($pool);

	    for ($item = 0; $item < $size; $item++) {
	      $code = $code . $pool[intval(floor(rand(0, ($stop - 1))))];
	    }

	    return $code;
	}

	public static function hash ($text = null) {
		if (isset($text)) {
			return md5($text);
		}

		return md5(uniqid(rand(), true));
	}

	public static function pull ($hash, $item, $data = null) {
		return (isset($hash[$item]) ? $hash[$item] : $data);
	}

	public static function pick ($test, $one, $two = null) {
		return ((func_num_args() % 2) ? ($test ? $one : $two) : ($test ? $test : $one));
	}
}