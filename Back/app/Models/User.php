<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function firm () {
    	return $this->hasOne(Firm::class, 'row', 'bind');
    }

    public function hand () {
    	return $this->hasOne(Hand::class, 'row', 'link');
    }

    public function lead () {
    	return $this->hasOne(Lead::class, 'row', 'link');
    }
}