<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
        'status',
        'img',
        'no_hp',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function pelanggan()
    {
        return $this->hasOne(Pelanggan::class);
    }

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }

    public function getProfilePhotoUrlAttribute()
    {
        return $this->img
            ? asset('storage/' . $this->img)
            : asset('img/default-user.png');
    }
    public function transaksi()
{
    return $this->hasMany(\App\Models\Transaksi::class);
}

    
    // Tidak ada accessor no_hp di sini
}
