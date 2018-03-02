<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomNumber extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'description',
        'max_capacity',
    ];
}