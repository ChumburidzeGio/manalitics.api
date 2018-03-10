<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\ModelExtensions\Cachable\CacheableEloquent;
use App\ModelExtensions\Validatable\ValidatingTrait;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Booking extends Model
{
    use ValidatingTrait;
    //use CacheableEloquent;

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'bookable_id',
        'bookable_type',
        'user_id',
        'user_type',
        'starts_at',
        'ends_at',
        'price',
        'currency',
        'price_equation',
        'cancelled_at',
        'notes',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'bookable_id' => 'integer',
        'bookable_type' => 'string',
        'user_id' => 'integer',
        'user_type' => 'string',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'price' => 'float',
        'currency' => 'string',
        'price_equation' => 'json',
        'cancelled_at' => 'datetime',
        'notes' => 'string',
    ];

    /**
     * {@inheritdoc}
     */
    protected $observables = [
        'validating',
        'validated',
    ];

    /**
     * The default rules that the model will validate against.
     *
     * @var array
     */
    protected $rules = [
        'bookable_id' => 'required|integer',
        'bookable_type' => 'required|string',
        'user_id' => 'required|integer',
        'user_type' => 'required|string',
        'starts_at' => 'required|date',
        'ends_at' => 'required|date',
        'price' => 'required|numeric',
        'currency' => 'required|alpha|size:3',
        'price_equation' => 'nullable|array',
        'cancelled_at' => 'nullable|date',
        'notes' => 'nullable|string|max:10000',
    ];

    /**
     * Whether the model should throw a
     * ValidationException if it fails validation.
     *
     * @var bool
     */
    protected $throwValidationExceptions = true;

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('rinvex.bookings.tables.bookings'));
    }

    /**
     * {@inheritdoc}
     */
    protected static function boot()
    {
        parent::boot();

        static::validating(function (self $booking) {
            list($price, $priceEquation, $currency) = is_null($booking->price)
                ? $booking->calculatePrice($booking->bookable, $booking->starts_at, $booking->ends_at) : [$booking->price, $booking->price_equation, $booking->currency];

            $booking->price_equation = $priceEquation;
            $booking->currency = $currency;
            $booking->price = $price;
        });
    }

    /**
     * Calculate the booking price.
     *
     * @param \Illuminate\Database\Eloquent\Model $bookable
     * @param string                              $startsAt
     * @param string                              $endsAt
     *
     * @return array
     */
    public static function calculatePrice(Model $bookable, Carbon $startsAt, Carbon $endsAt = null): array
    {
        switch ($bookable->unit) {
            case 'd':
                $method = 'addDay';
                break;
            case 'm':
                $method = 'addMinute';
                break;
            case 'h':
            default:
                $method = 'addHour';
                break;
        }

        $prices = $bookable->prices->map(function (BookingPrice $price) {
            return [
                'weekday' => $price->weekday,
                'starts_at' => $price->starts_at,
                'ends_at' => $price->ends_at,
                'percentage' => $price->percentage,
            ];
        });

        $totalUnits = 0;
        $totalPrice = 0;
        for ($date = clone $startsAt; $date->lt($endsAt ?? $date->addDay()); $date->$method()) {
            // Count units
            $totalUnits++;

            // Get applicable custom prices. Use first custom price matched, and ignore
            // others. We should not have multiple custom prices for same time range anyway!
            $customPrice = $prices->search(function ($price) use ($date, $bookable) {
                $dayMatched = $price['weekday'] === mb_strtolower($date->format('D'));

                return $bookable->unit === 'd' ? $dayMatched : $dayMatched && (new Carbon($date->format('H:i:s')))->between(new Carbon($price['starts_at']), new Carbon($price['ends_at']));
            });

            // Use custom price if exists (custom price is a +/- percentage of original resource price)
            $totalPrice += $customPrice !== false ? $bookable->price + (($bookable->price * $prices[$customPrice]['percentage']) / 100) : $bookable->price;
        }

        $rates = $bookable->rates->map(function (BookingRate $rate) {
            return [
                'percentage' => $rate->percentage,
                'operator' => $rate->operator,
                'amount' => $rate->amount,
            ];
        })->toArray();

        foreach ($rates as $rate) {
            switch ($rate['operator']) {
                case '^':
                    $units = $totalUnits <= $rate['amount'] ? $totalUnits : $rate['amount'];
                    $totalPrice += (($rate['percentage'] * $bookable->price) / 100) * $units;
                    break;
                case '>':
                    $totalPrice += $totalUnits > $rate['amount'] ? ((($rate['percentage'] * $bookable->price) / 100) * $totalUnits) : 0;
                    break;
                case '<':
                    $totalPrice += $totalUnits < $rate['amount'] ? ((($rate['percentage'] * $bookable->price) / 100) * $totalUnits) : 0;
                    break;
                case '=':
                default:
                    $totalPrice += $totalUnits === $rate['amount'] ? ((($rate['percentage'] * $bookable->price) / 100) * $totalUnits) : 0;
                    break;
            }
        }

        $priceEquation = [
            'price' => $bookable->price,
            'unit' => $bookable->unit,
            'currency' => $bookable->currency,
            'total_units' => $totalUnits,
            'total_price' => $totalPrice,
            'prices' => $prices,
            'rates' => $rates,
        ];

        return [$totalPrice, $priceEquation, $bookable->currency];
    }

    /**
     * Get the owning resource model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function bookable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the owning user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function user(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get bookings of the given resource.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param \Illuminate\Database\Eloquent\Model   $bookable
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfBookable(Builder $builder, Model $bookable): Builder
    {
        return $builder->where('bookable_type', $bookable->getMorphClass())->where('bookable_id', $bookable->getKey());
    }

    /**
     * Get bookings of the given user.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param \Illuminate\Database\Eloquent\Model   $user
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfUser(Builder $builder, Model $user): Builder
    {
        return $builder->where('user_type', $user->getMorphClass())->where('user_id', $user->getKey());
    }

    /**
     * Get the past bookings.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePast(Builder $builder): Builder
    {
        return $builder->whereNull('cancelled_at')
            ->whereNotNull('ends_at')
            ->where('ends_at', '<', now());
    }

    /**
     * Get the future bookings.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFuture(Builder $builder): Builder
    {
        return $builder->whereNull('cancelled_at')
            ->whereNotNull('starts_at')
            ->where('starts_at', '>', now());
    }

    /**
     * Get the current bookings.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCurrent(Builder $builder): Builder
    {
        return $builder->whereNull('cancelled_at')
            ->whereNotNull('starts_at')
            ->whereNotNull('ends_at')
            ->where('starts_at', '<', now())
            ->where('ends_at', '>', now());
    }

    /**
     * Get the cancelled bookings.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCancelled(Builder $builder): Builder
    {
        return $builder->whereNotNull('cancelled_at');
    }

    /**
     * Get bookings starts before the given date.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string                                $date
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStartsBefore(Builder $builder, string $date): Builder
    {
        return $builder->whereNull('cancelled_at')
            ->whereNotNull('starts_at')
            ->where('starts_at', '<', new Carbon($date));
    }

    /**
     * Get bookings starts after the given date.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string                                $date
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStartsAfter(Builder $builder, string $date): Builder
    {
        return $builder->whereNull('cancelled_at')
            ->whereNotNull('starts_at')
            ->where('starts_at', '>', new Carbon($date));
    }

    /**
     * Get bookings starts between the given dates.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string                                $startsAt
     * @param string                                $endsAt
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStartsBetween(Builder $builder, string $startsAt, string $endsAt): Builder
    {
        return $builder->whereNull('cancelled_at')
            ->whereNotNull('starts_at')
            ->where('starts_at', '>=', new Carbon($startsAt))
            ->where('starts_at', '<=', new Carbon($endsAt));
    }

    /**
     * Get bookings ends before the given date.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string                                $date
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEndsBefore(Builder $builder, string $date): Builder
    {
        return $builder->whereNull('cancelled_at')
            ->whereNotNull('ends_at')
            ->where('ends_at', '<', new Carbon($date));
    }

    /**
     * Get bookings ends after the given date.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string                                $date
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEndsAfter(Builder $builder, string $date): Builder
    {
        return $builder->whereNull('cancelled_at')
            ->whereNotNull('ends_at')
            ->where('ends_at', '>', new Carbon($date));
    }

    /**
     * Get bookings ends between the given dates.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string                                $startsAt
     * @param string                                $endsAt
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEndsBetween(Builder $builder, string $startsAt, string $endsAt): Builder
    {
        return $builder->whereNull('cancelled_at')
            ->whereNotNull('ends_at')
            ->where('ends_at', '>=', new Carbon($startsAt))
            ->where('ends_at', '<=', new Carbon($endsAt));
    }

    /**
     * Get bookings cancelled before the given date.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string                                $date
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCancelledBefore(Builder $builder, string $date): Builder
    {
        return $builder->whereNotNull('cancelled_at')
            ->where('cancelled_at', '<', new Carbon($date));
    }

    /**
     * Get bookings cancelled after the given date.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string                                $date
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCancelledAfter(Builder $builder, string $date): Builder
    {
        return $builder->whereNotNull('cancelled_at')
            ->where('cancelled_at', '>', new Carbon($date));
    }

    /**
     * Get bookings cancelled between the given dates.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string                                $startsAt
     * @param string                                $endsAt
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCancelledBetween(Builder $builder, string $startsAt, string $endsAt): Builder
    {
        return $builder->whereNotNull('cancelled_at')
            ->where('cancelled_at', '>=', new Carbon($startsAt))
            ->where('cancelled_at', '<=', new Carbon($endsAt));
    }

    /**
     * Get bookings between the given dates.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string                                $startsAt
     * @param string                                $endsAt
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRange(Builder $builder, string $startsAt, string $endsAt): Builder
    {
        return $builder->whereNull('cancelled_at')
            ->whereNotNull('starts_at')
            ->where('starts_at', '>=', new Carbon($startsAt))
            ->where(function (Builder $builder) use ($endsAt) {
                $builder->whereNull('ends_at')
                    ->orWhere(function (Builder $builder) use ($endsAt) {
                        $builder->whereNotNull('ends_at')
                            ->where('ends_at', '<=', new Carbon($endsAt));
                    });
            });
    }

    /**
     * Check if the booking is cancelled.
     *
     * @return bool
     */
    public function isCancelled(): bool
    {
        return (bool) $this->cancelled_at;
    }

    /**
     * Check if the booking is past.
     *
     * @return bool
     */
    public function isPast(): bool
    {
        return ! $this->isCancelled() && $this->ends_at->isPast();
    }

    /**
     * Check if the booking is future.
     *
     * @return bool
     */
    public function isFuture(): bool
    {
        return ! $this->isCancelled() && $this->starts_at->isFuture();
    }

    /**
     * Check if the booking is current.
     *
     * @return bool
     */
    public function isCurrent(): bool
    {
        return ! $this->isCancelled() && now()->between($this->starts_at, $this->ends_at);
    }
}