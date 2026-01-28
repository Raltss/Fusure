<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobPosting extends Model
{
    protected $fillable = [
        'company_id',
        'title',
        'description',
        'type',
        'location',
        'salary_min',
        'salary_max',
        'requirements',
        'responsibilities',
        'benefits',
        'status',
        'deadline',
    ];

    protected function casts(): array
    {
        return [
            'deadline' => 'datetime',
        ];
    }

    // Relationship: Job posting belongs to a company
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }
}
