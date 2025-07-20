<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class School extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'address',
        'logo_path',
        'description',
        'candidate_limit',
        'current_active_candidate_count',
        'is_active',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
        'candidate_limit' => 'integer',
        'current_active_candidate_count' => 'integer',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        // Auto-generate slug when creating a new school
        static::creating(function ($school) {
            if (empty($school->slug)) {
                $school->slug = Str::slug($school->name);
            }
        });
    }

    /**
     * Get users associated with this school.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get admin users associated with this school.
     */
    public function admins(): HasMany
    {
        return $this->hasMany(User::class)->whereHas('role', function ($query) {
            $query->where('name', 'admin');
        });
    }

    /**
     * Get candidate users associated with this school.
     */
    public function candidates(): HasMany
    {
        return $this->hasMany(User::class)->whereHas('role', function ($query) {
            $query->where('name', 'candidate');
        });
    }

    /**
     * Get inspector users associated with this school.
     */
    public function inspectors(): HasMany
    {
        return $this->hasMany(User::class)->whereHas('role', function ($query) {
            $query->where('name', 'inspector');
        });
    }

    /**
     * Get the active candidates count.
     * This method explicitly counts only users with the 'candidate' role
     * to ensure admins and inspectors don't count toward the limit.
     */
    public function activeCandidatesCount(): int
    {
        return $this->candidates()->where('is_active', true)->count();
    }

    /**
     * Update the current_active_candidate_count based on actual active candidates.
     * 
     * @return int The updated count
     */
    public function updateActiveCandidateCount(): int
    {
        $activeCount = $this->activeCandidatesCount();
        $this->current_active_candidate_count = $activeCount;
        $this->save();
        
        return $activeCount;
    }

    /**
     * Check if the school has capacity for more candidates.
     * This method uses the live count of active candidates (not admins or inspectors)
     * to ensure accurate enforcement of limits.
     * 
     * An extra slot (+1) is automatically added to the limit to provide buffer capacity.
     * 
     * @return bool
     */
    public function hasCapacity(): bool
    {
        $activeCount = $this->activeCandidatesCount();
        
        // Update the stored count if it doesn't match the actual count
        if ($activeCount !== $this->current_active_candidate_count) {
            $this->current_active_candidate_count = $activeCount;
            $this->save();
        }
        
        // Add 1 to the limit to provide an extra buffer slot
        return $activeCount < ($this->candidate_limit + 1);
    }

    /**
     * Get the remaining capacity for candidates.
     * 
     * An extra slot (+1) is automatically added to the limit to provide buffer capacity.
     * 
     * @return int
     */
    public function getRemainingCapacity(): int
    {
        $activeCount = $this->activeCandidatesCount();
        return max(0, ($this->candidate_limit + 1) - $activeCount);
    }

    /**
     * Relationship to the user who created this school.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relationship to the user who last updated this school.
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Courses associated with this school.
     */
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    /**
     * Exams associated with this school.
     */
    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class);
    }
}