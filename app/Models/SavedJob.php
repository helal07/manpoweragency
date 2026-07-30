<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavedJob extends Model
{
    protected $fillable = [
        'user_id',
        'job_circular_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jobCircular()
    {
        return $this->belongsTo(JobCircular::class);
    }
}
