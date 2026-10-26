<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const ROLES = [
        'pemohon' => 'Pemohon',
        'admin'   => 'Admin / Petugas SarPras',
    ];

    protected $fillable = ['name', 'email', 'password', 'role', 'jabatan', 'unit'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function pengajuan()
    {
        return $this->hasMany(Pengajuan::class);
    }

    public function getLabelPeranAttribute(): string
    {
        return self::ROLES[$this->role] ?? $this->role;
    }
}
