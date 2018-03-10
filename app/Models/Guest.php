<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\ModelExtensions\Bookable\HasBookings;


class Guest extends Model
{
    use HasBookings;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'phone', 'email', 'country'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'first_name' => 'string',
        'last_name' => 'string',
        'phone' => 'string',
        'email' => 'string',
        'country' => 'string',
    ];
}