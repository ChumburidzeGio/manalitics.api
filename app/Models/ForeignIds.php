<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForeignIds extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'model_id',
        'model_type',
        'service',
        'foreign_id',
    ];
}