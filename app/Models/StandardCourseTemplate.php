<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StandardCourseTemplate extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'permit_category_id',
        'sequence_order',
        'exam_section',
        'default_materials',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'default_materials' => 'array',
    ];

    /**
     * Get the permit category that this course template belongs to.
     */
    public function permitCategory(): BelongsTo
    {
        return $this->belongsTo(PermitCategory::class);
    }
}
