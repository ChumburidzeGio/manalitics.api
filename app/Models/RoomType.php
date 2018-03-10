<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\ModelExtensions\HasForeignIds\HasForeignIds;
use App\ModelExtensions\Bookable\Bookable;

class RoomType extends Model
{
    use HasForeignIds, Bookable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        //'name',
        'property_id',
        'price',
        'unit',
        'currency',
    ];
}