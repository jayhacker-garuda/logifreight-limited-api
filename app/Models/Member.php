<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class Member extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $guard = 'member';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'umi', //Unique Member Id
        'full_name',
        'email',
        'trn',
        'address',
        'mail_box',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function quick_alerts()
    {
        return $this->hasMany(QuickAlert::class, 'member_umi', 'umi');
    }
}