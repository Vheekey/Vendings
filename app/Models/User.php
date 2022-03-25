<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [];

    //roles
    const SELLER = 'seller';
    const BUYER = 'buyer';

    public function userable()
    {
        return $this->morphTo();
    }

    public function getRoleClass(string $role)
    {
        return [
            User::BUYER => Buyer::class,
            User::SELLER => Seller::class,
        ][strtolower($role)];
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class, 'userable_id');
    }

    public function buyer()
    {
        return $this->belongsTo(Buyer::class, 'userable_id');
    }
}
