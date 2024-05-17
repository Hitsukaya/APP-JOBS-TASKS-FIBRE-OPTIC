<?php

namespace App\Models\Zeroloss;

use App\Models\User;
use App\Models\Zeroloss\Build;
use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Task extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'zeroloss_tasks';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'number',
        //'status',
        'notes',
    ];

    protected $casts = [
         'status' => TaskStatus::class,
     ];

    /** @return MorphOne<TaskAddress> */
    public function address(): MorphOne
    {
        return $this->morphOne(TaskAddress::class, 'addressable');
    }

    /** @return BelongsTo<Engineer,self> */
    public function engineer(): BelongsTo
    {
        return $this->belongsTo(Engineer::class, 'zeroloss_engineer_id');

    }

   /** @return BelongsTo<User,self> */
   public function user(): BelongsTo
   {
       return $this->belongsTo(User::class, 'user_id');

   }

    // /** @return BelongsTo<Build,self> */
    // public function build(): BelongsTo
    // {
    //     return $this->belongsTo(Build::class, 'zeroloss_build_id');
    // }

    /** @return HasMany<TaskItem> */
    public function items(): HasMany
    {
        return $this->hasMany(TaskItem::class, 'zeroloss_task_id');
    }

    // /** @return BelongsToMany<Build> */
    // public function categories(): BelongsToMany
    // {
    //     return $this->belongsToMany(Build::class, 'zeroloss_build_job', 'zeroloss_job_id', 'zeroloss_build_id')->withTimestamps();
    // }

    // /** @return HasMany<Payment> */
    // public function payments(): HasMany
    // {
    //     return $this->hasMany(Payment::class);
    // }
}
