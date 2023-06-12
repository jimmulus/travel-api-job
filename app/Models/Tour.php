<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Tour extends Model
{
    use HasFactory;
    use HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'travel_id',
        'name',
        'starting_date',
        'ending_date',
        'price',
    ];


    /**
     * ATTRIBUTES
     */

    public function price(): Attribute
    {
        return new Attribute(
            get: fn ($value) => ($value / 100),
            set: fn ($value) => ($value * 100)
        );
    }


    /**
     * RELATIONS
     */

    public function travel(): BelongsTo
    {
        return $this->belongsTo(Travel::class);
    }

}
