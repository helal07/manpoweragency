<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Applicant extends Authenticatable implements HasMedia
{
    use HasFactory, Notifiable, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'nid_passport',
        'password',
        'current_address',
        'permanent_address',
        'linkedin_url',
        'resume_path',
        // Profile details
        'fathers_name',
        'mothers_name',
        'mobile_no',
        'date_of_birth',
        'gender',
        'marital_status',
        // Education
        'ssc_year',
        'ssc_result',
        'hsc_year',
        'hsc_result',
        'highest_education',
        // Experience & Skills
        'experience_details',
        'experience_years',
        'can_speak_english',
        'english_proficiency',
        'other_languages',
        // Travel & Documents
        'preferred_country',
        'passport_expiry',
        // Emergency Contact
        'emergency_contact_name',
        'emergency_contact_phone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
            'passport_expiry' => 'date',
            'can_speak_english' => 'boolean',
            'ssc_year' => 'integer',
            'hsc_year' => 'integer',
            'experience_years' => 'integer',
        ];
    }
    
    public function jobApplications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }
    
    public function savedJobs(): HasMany
    {
        return $this->hasMany(SavedJob::class);
    }

    public function customFieldValues(): HasMany
    {
        return $this->hasMany(CustomFieldValue::class);
    }

    /**
     * Get the value of a specific custom field by its ID or name.
     */
    public function getCustomFieldValue(int|string $fieldIdOrName): ?string
    {
        $query = $this->customFieldValues();

        if (is_numeric($fieldIdOrName)) {
            $query->where('custom_field_id', $fieldIdOrName);
        } else {
            $query->whereHas('customField', fn ($q) => $q->where('name', $fieldIdOrName));
        }

        return $query->first()?->value;
    }
}
