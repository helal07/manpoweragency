<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo',
        'country',
        'sector',
        'website_url',
        'order',
    ];

    /**
     * Get URL for client logo with fallback check.
     */
    public function getLogoUrlAttribute(): ?string
    {
        if (!empty($this->logo)) {
            if (filter_var($this->logo, FILTER_VALIDATE_URL)) {
                return $this->logo;
            }
            return asset('storage/' . $this->logo);
        }

        return null;
    }

    /**
     * Get two-letter initials for company fallback avatar.
     */
    public function getInitialsAttribute(): string
    {
        $words = preg_split('/\s+/', trim($this->name));
        $initials = '';
        foreach (array_slice($words, 0, 2) as $w) {
            $initials .= strtoupper(mb_substr($w, 0, 1));
        }
        return $initials ?: 'CL';
    }
}
