<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Silber\Bouncer\Database\HasRolesAndAbilities;
use App\ModelExtensions\HasForeignIds\HasForeignIds;
use App\ModelExtensions\Bookable\BookingScopes;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable,
        Authorizable,
        HasRolesAndAbilities,
        HasForeignIds,
        BookingScopes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email',
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
     * The user may have many bookings.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function bookings(): MorphMany
    {
        return $this->morphMany(config('rinvex.bookings.models.booking'), 'user');
    }

    /**
     * Get bookings of the given resource.
     *
     * @param \Illuminate\Database\Eloquent\Model $bookable
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function bookingsOf(Model $bookable): MorphMany
    {
        return $this->bookings()->where('bookable_type', $bookable->getMorphClass())->where('bookable_id', $bookable->getKey());
    }

    /**
     * Check if the person booked the given model.
     *
     * @param \Illuminate\Database\Eloquent\Model $bookable
     *
     * @return bool
     */
    public function isBooked(Model $bookable): bool
    {
        return $this->bookings()->where('bookable_id', $bookable->getKey())->exists();
    }

    /**
     * Book the given model at the given dates with the given price.
     *
     * @param \Illuminate\Database\Eloquent\Model $bookable
     * @param string                              $startsAt
     * @param string                              $endsAt
     *
     * @return Model
     */
    public function newBooking(Model $bookable, string $startsAt, string $endsAt): Booking
    {
        return $this->bookings()->create([
            'bookable_id' => $bookable->getKey(),
            'bookable_type' => $bookable->getMorphClass(),
            'user_id' => $this->getKey(),
            'user_type' => $this->getMorphClass(),
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
        ]);
    }
}