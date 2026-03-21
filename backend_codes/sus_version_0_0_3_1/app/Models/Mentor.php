<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mentor extends Model
{
    protected $table = 'mentors';
    protected $primaryKey = 'mentor_id';

    // No timestamps in your mentors table
    public $timestamps = false;

    // Fillable fields (match your table columns)
    protected $fillable = [
        'user_id',
        'name',
        'surname',
        'email',
        'phone_number',
        'department',
        'office_location',
        'office_hours',
        'bio',
        'nationality',
        'languages',
        'profile_data',     // jsonb column
    ];

    // Cast jsonb field properly
    protected $casts = [
        'profile_data' => 'array',      // or 'json' if you prefer
    ];

    /**
     * The user this mentor profile belongs to
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}