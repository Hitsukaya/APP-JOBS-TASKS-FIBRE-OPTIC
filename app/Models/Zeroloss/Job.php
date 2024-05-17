<?php

namespace App\Models\Zeroloss;

use App\Models\Comment;
use App\Enums\TaskStatus;
use App\Models\Zeroloss\ItemTask;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Job extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    /**
     * @var string
     */
    protected $table = 'zeroloss_jobs';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        //'number',
        'status',
        //'notes',
    ];



    /**
     * @var array<string, string>
     */
    protected $casts = [
        'featured' => 'boolean',
        'is_visible' => 'boolean',
        'backorder' => 'boolean',
        'requires_shipping' => 'boolean',
        'published_at' => 'date',
        //'status' => TaskStatus::class,
    ];

    /** @return BelongsTo<Brand,self> */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'zeroloss_brand_id');
    }

    /** @return BelongsToMany<Build> */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Build::class, 'zeroloss_build_job', 'zeroloss_job_id', 'zeroloss_build_id')->withTimestamps();
    }

    /** @return MorphMany<Comment> */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /** @return HasMany<TaskItem> */
    public function items(): HasMany
    {
        return $this->hasMany(TaskItem::class, 'zeroloss_task_id');
    }
}
