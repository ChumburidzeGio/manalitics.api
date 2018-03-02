<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Guest extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'display_name', 'phone', 'email', 'default_address_id', 'accepts_marketing'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'first_name' => 'string',
        'last_name' => 'string',
        'display_name' => 'string',
        'phone' => 'string',
        'email' => 'string',
        'default_address_id' => 'integer',
        'accepts_marketing' => 'boolean',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the default address associated with the guest.
     */
    public function default_address()
    {
        return $this->hasOne(Address::class, 'id', 'default_address_id');
    }

    /**
     * Get the addresses associated with the guest.
     */
    public function addresses()
    {
        return $this->hasMany(Address::class, 'guest_id');
    }

    /**
     * Get the reservations associated with the guest.
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'guest_id');
    }
}