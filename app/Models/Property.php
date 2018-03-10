<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\ModelExtensions\HasForeignIds\HasForeignIds;

class Property extends Model
{
    use HasForeignIds;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'phone',
    ];

    /**
     * Get the rooms associated with the property.
     */
    public function rooms()
    {
        return $this->hasMany(RoomType::class, 'property_id');
    }
}