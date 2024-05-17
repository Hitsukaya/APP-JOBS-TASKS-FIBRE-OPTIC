<?php

namespace App\Models;

use App\Models\Zeroloss\Engineer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Comment extends Model
{
    use HasFactory;

    protected $table = 'comments';

    protected $guarded = [];

    protected $casts = [
        'is_visible' => 'boolean',
    ];

    /** @return BelongsTo<Engineer,self> */
    public function engineer(): BelongsTo
    {
        return $this->belongsTo(Engineer::class);
    }

    /** @return MorphTo<Model,self> */
    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }
}
