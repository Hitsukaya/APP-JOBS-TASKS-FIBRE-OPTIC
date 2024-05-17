<?php

namespace App\Models\Zeroloss;

use App\Enums\TaskStatus;
use App\Models\Zeroloss\Task;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaskItem extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    /**
     * @var string
     */
    protected $table = 'zeroloss_task_items';

     /**
     * @var array<int, string>
     */
    protected $fillable = [
        //'number',
        'status',
        //'notes',
    ];

    protected $casts = [
        'status' => TaskStatus::class,
    ];

    /** @return BelongsTo<Job,self> */
    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class, 'zeroloss_job_id');
    }

    /** @return BelongsTo<Task,self> */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'zeroloss_task_id');
    }

    /** @return BelongsTo<Build,self> */
    public function build(): BelongsTo
    {
        return $this->belongsTo(Build::class, 'zeroloss_build_id');  
    }

    /** @return BelongsTo<Brand,self> */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'zeroloss_brand_id');  
    }

    // /** @return HasMany<Job,Build,self> */
    // public function items(): HasMany
    // {
    //     return $this->hasMany(Job::class, 'zeroloss_job_id');
    //     return $this->hasMany(Build::class, 'zeroloss_build_id');
    // }   
    // /** @return BelongsTo<Task,self> */
    // public function task(): BelongsTo
    // {
    //     return $this->belongsTo(Task::class, 'zeroloss_task_id');
    // }

    //   /** @return HasMany<Task> */
    //   public function items(): HasMany
    //   {
    //       return $this->hasMany(Task::class, 'zeroloss_task_item_id');       
    //   }

}
