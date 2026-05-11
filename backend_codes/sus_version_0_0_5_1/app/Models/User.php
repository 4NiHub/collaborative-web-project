<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'user_id';

    public $incrementing = true;
    protected $keyType = 'int';

    public $timestamps = false;

    protected $dates = ['creation_time'];

    protected $fillable = [
        'role_id',
        'email',
        'password_hash',
        'creation_time',
    ];

    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    // ── Relationships ────────────────────────────────────────────────────────
    public function student()
    {
        return $this->hasOne(Student::class, 'user_id', 'user_id');
    }

    public function staff()
    {
        return $this->hasOne(Staff::class, 'user_id', 'user_id');
    }

    // ── ADD THIS ─────────────────────────────────────────────────────────────
    public function mentor()
    {
        return $this->hasOne(Mentor::class, 'user_id', 'user_id');
    }

    public function getFullNameAttribute()
    {
        if ($this->student) {
            return $this->student->name . ' ' . $this->student->surname;
        }
        return $this->email;
    }
}