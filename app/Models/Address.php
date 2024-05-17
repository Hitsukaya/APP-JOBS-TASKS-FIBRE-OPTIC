<?php

namespace App\Models;

use App\Models\Zeroloss\Brand;
use App\Models\Zeroloss\Engineer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Address extends Model
{
    use HasFactory;

    protected $table = 'addresses';

    /** @return MorphToMany<Engineer> */
    public function engineer(): MorphToMany
    {
        return $this->morphedByMany(Engineer::class, 'addressable');
    }

    /** @return MorphToMany<Brand> */
    public function brands(): MorphToMany
    {
        return $this->morphedByMany(Brand::class, 'addressable');
    }
}
