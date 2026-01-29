<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization
     *
     * @var list<string>
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
        ];
    }

    public function company(): HasOne
    {
        return $this->hasOne(Company::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function savedJobs(): BelongsToMany
    {
        return $this->belongsToMany(JobPosting::class, 'saved_jobs')
            ->withTimestamps();
    }

    /**
     * Save a job posting
     */
    public function saveJob(JobPosting $jobPosting): void
    {
        if (! $this->hasSavedJob($jobPosting)) {
            $this->savedJobs()->attach($jobPosting->id);
        }
    }

    /**
     * Unsave a job posting
     */
    public function unsaveJob(JobPosting $jobPosting): void
    {
        $this->savedJobs()->detach($jobPosting->id);
    }

    /**
     * Check if user has saved a job
     */
    public function hasSavedJob(JobPosting $jobPosting): bool
    {
        return $this->savedJobs()->where('job_posting_id', $jobPosting->id)->exists();
    }

    /**
     * Toggle save status
     */
    public function toggleSaveJob(JobPosting $jobPosting): bool
    {
        if ($this->hasSavedJob($jobPosting)) {
            $this->unsaveJob($jobPosting);

            return false; // Unsaved
        } else {
            $this->saveJob($jobPosting);

            return true; // Saved
        }
    }
}
