<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    protected $fillable = [
        'applicant_id',
        'job_circular_id',
        'status',
        'cover_letter',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(Applicant::class, 'applicant_id');
    }

    public function jobCircular()
    {
        return $this->belongsTo(JobCircular::class);
    }
}
