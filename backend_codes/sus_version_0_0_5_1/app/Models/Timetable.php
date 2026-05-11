<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timetable extends Model
{
    protected $table = 'timetable';
    protected $primaryKey = 'session_id';
    public $incrementing = true;           // still auto-increment via sequence
    protected $keyType = 'int';

    protected $fillable = [
        'subject_group_id', 'time_slot', 'day_slot',
        'room_number', 'session_type', 'building'
    ];

    public $timestamps = false;
}