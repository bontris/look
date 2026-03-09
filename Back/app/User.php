<?php

namespace App;

use Illuminate\Notifications\Notifiable;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    const ROOT = 0x01;

    const TEAM = 0x02;

    const LITE = 0x03;

    const LIVE = 0x01;

    const GONE = 0x02;

    const BUSY = 0x03;

    const SIGN = 0x01;

    const READ = 0x02;

    const MAKE = 0x03;

    const SAVE = 0x04;

    const DROP = 0x05;

    const LOCK = 0x06;

    const POST = 0x07;

    const GIVE = 0x08;

    const DUMP = 0x09;

    const BULK = 0x10;

    protected $casts = [
        'creation' => 'datetime',
        'deletion' => 'datetime'
    ];

    protected $table = 'users';

    protected $hidden = ['pass'];

    protected $fillable = [
        'type',
        'link',
        'tone',
        'role',
        'team',
        'zone',
        'nick',
        'last',
        'name',
        'mail',
        'work',
        'face'
    ];

    public function firm () {
    	return $this->hasOne(Firm::class, 'row', 'bind');
    }
}