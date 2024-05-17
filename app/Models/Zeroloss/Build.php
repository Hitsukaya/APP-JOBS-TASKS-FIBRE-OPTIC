<?php

namespace App\Models\Zeroloss;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Build extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    /**
     * @var string
     */
    protected $table = 'zeroloss_builds';

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'is_visible' => 'boolean',
    ];

    /** @return HasMany<Build> */
    public function children(): HasMany
    {
        return $this->hasMany(Build::class, 'parent_id');
    }

    /** @return BelongsTo<Build,self> */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Build::class, 'parent_id');
    }

    /** @return BelongsToMany<Job> */
    public function jobs(): BelongsToMany
    {
        return $this->belongsToMany(Job::class, 'zeroloss_build_job', 'zeroloss_build_id', 'zeroloss_job_id');
    }
}
