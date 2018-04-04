<?php
/**
 * Created by PhpStorm.
 * User: giorgi
 * Date: 3/30/18
 * Time: 5:05 PM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\ModelExtensions\HasForeignIds\HasForeignIds;

class Merchant extends Model
{
    use HasForeignIds;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pos_id', 'title', 'category_id', 'is_generic'
    ];
}