<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $table = 'groups';  // your table name in v_2 schema
    protected $primaryKey = 'group_id';

    public $timestamps = false;  // no created_at/updated_at

    protected $fillable = [
        'group_name',
        'group_level',
        'is_active',
        'creation_date'
    ];
}