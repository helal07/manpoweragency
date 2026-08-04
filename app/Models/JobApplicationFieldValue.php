<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobApplicationFieldValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_application_id',
        'custom_field_id',
        'value',
    ];

    public function jobApplication(): BelongsTo
    {
        return $this->belongsTo(JobApplication::class);
    }

    public function customField(): BelongsTo
    {
        return $this->belongsTo(CustomField::class);
    }
}
