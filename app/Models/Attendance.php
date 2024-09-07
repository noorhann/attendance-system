<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'check_in', 'check_out'];

    protected $casts = [
        'check_in' => 'datetime',
        'check_out' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getHoursWorkedAttribute()
    {
        if ($this->check_in && $this->check_out) {
            $diff = $this->check_in->diff($this->check_out);

            $hours = $diff->h;
            $minutes = $diff->i;

            return [
                'hours' => $hours,
                'minutes' => $minutes,
            ];
        }
        return [
            'hours' => 0,
            'minutes' => 0,
        ];
    }

}
