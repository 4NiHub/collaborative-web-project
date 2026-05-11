<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'students';
    protected $primaryKey = 'student_id';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'name',
        'surname',
        'entry_year',
        'group_id',
        'phone_number',
    ];

    // Optional: relations
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }
}