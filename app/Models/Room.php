<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'number',
        'name',
        'status',
        'smoke',
        'type_id',
    ];

    /**
     * Get the type record associated with the room.
     */
    public function type()
    {
        return $this->hasOne(RoomType::class, 'id', 'type_id');
    }
}