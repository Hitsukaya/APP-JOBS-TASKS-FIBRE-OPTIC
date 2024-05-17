<?php

namespace App\Models\Zeroloss;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class TaskAddress extends Model
{
    use HasFactory;

    protected $table = 'zeroloss_task_addresses';

    /** @return MorphTo<Model,self> */
    public function addressable(): MorphTo
    {
        return $this->morphTo();
    }
}
