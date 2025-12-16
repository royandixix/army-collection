<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAlamat extends Model
{
    use HasFactory;

    protected $table = 'user_alamats';

    protected $fillable = [
        'user_id',
        'label',
        'nama_penerima',
        'no_hp',
        'alamat',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    // 🔗 Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
