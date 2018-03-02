<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyType extends Model
{
//    const SEED_DATA = [
//        'Apartment' => 'Furnished, independent accommodations available for short- and long-term rental',
//        'Hotel' => 'Accommodations for travelers often with restaurants, meeting rooms and other guest services',
//        'Vacation Home' => 'Freestanding home with private, external entrance and rented specifically for vacation',
//        'Guesthouse' => 'Private home with separate living facilities for host and guest',
//        'Bed and Breakfast' => 'Private home offering overnight stays and breakfast',
//    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
    ];
}