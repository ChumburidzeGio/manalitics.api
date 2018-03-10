<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\ModelExtensions\Bookable\BookingScopes;

class BookableUnit extends Model
{
    use BookingScopes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
    ];

    /**
     * The resource may have many bookings.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function bookings(): MorphMany
    {
        return $this->morphMany(Booking::class, 'bookable');
    }

    /**
     * Get bookings of the given user.
     *
     * @param \Illuminate\Database\Eloquent\Model $user
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function bookingsOf(Model $user): MorphMany
    {
        return $this->bookings()->where('user_type', $user->getMorphClass())->where('user_id', $user->getKey());
    }

    /**
     * The resource may have many rates.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function rates(): MorphMany
    {
        return $this->morphMany(BookingRate::class, 'bookable');
    }

    /**
     * The resource may have many prices.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function prices(): MorphMany
    {
        return $this->morphMany(BookingPrice::class, 'bookable');
    }

    /**
     * Book the model for the given user at the given dates with the given price.
     *
     * @param \Illuminate\Database\Eloquent\Model $user
     * @param string                              $startsAt
     * @param string                              $endsAt
     *
     * @return \App\Models\Booking
     */
    public function newBooking(Model $user, string $startsAt, string $endsAt): Booking
    {
        return $this->bookings()->create([
            'bookable_id' => static::getKey(),
            'bookable_type' => static::getMorphClass(),
            'user_id' => $user->getKey(),
            'user_type' => $user->getMorphClass(),
            'starts_at' => (new Carbon($startsAt))->toDateTimeString(),
            'ends_at' => (new Carbon($endsAt))->toDateTimeString(),
        ]);
    }

    /**
     * Create a new booking rate.
     *
     * @param float  $percentage
     * @param string $operator
     * @param int    $amount
     *
     * @return \App\Models\BookingRate
     */
    public function newRate(float $percentage, string $operator, int $amount): BookingRate
    {
        return $this->rates()->create([
            'bookable_id' => static::getKey(),
            'bookable_type' => static::getMorphClass(),
            'percentage' => $percentage,
            'operator' => $operator,
            'amount' => $amount,
        ]);
    }

    /**
     * Create a new booking price.
     *
     * @param string $weekday
     * @param string $startsAt
     * @param string $endsAt
     * @param float  $percentage
     *
     * @return \App\Models\BookingPrice
     */
    public function newPrice(string $weekday, string $startsAt, string $endsAt, float $percentage): BookingPrice
    {
        return $this->prices()->create([
            'bookable_id' => static::getKey(),
            'bookable_type' => static::getMorphClass(),
            'percentage' => $percentage,
            'weekday' => $weekday,
            'starts_at' => (new Carbon($startsAt))->toTimeString(),
            'ends_at' => (new Carbon($endsAt))->toTimeString(),
        ]);
    }

}