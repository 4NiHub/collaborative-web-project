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

    // Disable automatic timestamps
    public $timestamps = false;

    // If you want to cast creation_time as date
    protected $dates = ['creation_time'];

    protected $fillable = [
        'email', 'password_hash', 'role_id', 'creation_time'
    ];

    // Tell Laravel where the password is stored
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    // ── RELATIONSHIPS ──
    public function student()
    {
        return $this->hasOne(Student::class, 'user_id', 'user_id');
    }

    public function staff()
    {
        return $this->hasOne(Staff::class, 'user_id', 'user_id');
    }

    // Uncomment if you need mentor relation later
    // public function mentor()
    // {
    //     return $this->hasOne(Mentor::class, 'user_id', 'user_id');
    // }

    // Optional: accessor for full name (useful in views/API)
    public function getFullNameAttribute()
    {
        if ($this->student) {
            return $this->student->name . ' ' . $this->student->surname;
        }
        return $this->email; // fallback
    }
}