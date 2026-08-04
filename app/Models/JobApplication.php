<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'applicant_id',
        'job_circular_id',
        'status',
        'cover_letter',
        'notes',
    ];

    public function applicant(): BelongsTo
    {
        return $this->belongsTo(Applicant::class, 'applicant_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(Applicant::class, 'applicant_id');
    }

    public function jobCircular(): BelongsTo
    {
        return $this->belongsTo(JobCircular::class);
    }

    public function customFieldValues(): HasMany
    {
        return $this->hasMany(JobApplicationFieldValue::class);
    }
}
