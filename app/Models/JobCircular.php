<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class JobCircular extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'title',
        'slug',
        'country',
        'category',
        'vacancy',
        'salary_range',
        'deadline',
        'status',
        'description',
        'requirements',
        'posted_at',
    ];

    protected $casts = [
        'deadline' => 'date',
        'posted_at' => 'datetime',
    ];

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function customFields(): BelongsToMany
    {
        return $this->belongsToMany(CustomField::class, 'job_circular_custom_field')
            ->withPivot('is_required', 'sort_order')
            ->withTimestamps()
            ->orderBy('job_circular_custom_field.sort_order');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('circular-image')
            ->singleFile();

        $this->addMediaCollection('circular-attachments');
    }
}
