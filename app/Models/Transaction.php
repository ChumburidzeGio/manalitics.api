<?php
/**
 * Created by PhpStorm.
 * User: giorgi
 * Date: 3/30/18
 * Time: 5:05 PM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Transaction extends Model
{
    use Sluggable;
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'original' => 'array',
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'reference' => [
                'onUpdate' => true,
                'source' => ['bank', 'title', 'date', 'description', 'amount', 'user_id'],
                'separator' => '',
                'unique' => false,
                'method' => function ($string) {
                    return md5($string);
                },
            ],
        ];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'bank', 'title', 'date', 'description', 'amount', 'user_id',
        'type', 'currency', 'is_expense', 'original'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'original', 'created_at', 'updated_at'
    ];
}