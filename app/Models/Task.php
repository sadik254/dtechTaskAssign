<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tasks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'timestamp',
        'name',
        'phone',
        'city',
        'address',
        'appointment_date',
        'appointment_time',
        'quality_report',
        'google_maps',
        'recording_link',
        'prefect_pitch',
        'assignee',
        'comment',
    ];

    /**
     * Get the user assigned to the task.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'assignee');
    }
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assignee');
    }
}
