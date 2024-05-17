<?php

namespace App\Models\Zeroloss;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'zeroloss_payments';

    protected $guarded = [];

    /** @return BelongsTo<Task,self> */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}
