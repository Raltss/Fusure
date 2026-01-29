<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Application extends Model
{
    protected $fillable = [
        'user_id',
        'job_posting_id',
        'resume',
        'cover_letter',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    // Relationship: Application belongs to a user (job seeker)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relationship: Application belongs to a job posting
    public function jobPosting(): BelongsTo
    {
        return $this->belongsTo(JobPosting::class);
    }
}
