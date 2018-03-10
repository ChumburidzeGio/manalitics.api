<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\ModelExtensions\Cachable\CacheableEloquent;
use App\ModelExtensions\Validatable\ValidatingTrait;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class BookingPrice extends Model
{
    use ValidatingTrait;
    //use CacheableEloquent;

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'bookable_id',
        'bookable_type',
        'percentage',
        'weekday',
        'starts_at',
        'ends_at',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'bookable_id' => 'integer',
        'bookable_type' => 'string',
        'percentage' => 'float',
        'weekday' => 'string',
        'starts_at' => 'string',
        'ends_at' => 'string',
    ];

    /**
     * {@inheritdoc}
     */
    protected $observables = [
        'validating',
        'validated',
    ];

    public function getTable()
    {
        return 'booking_prices';
    }

    /**
     * The default rules that the model will validate against.
     *
     * @var array
     */
    protected $rules = [
        'bookable_id' => 'required|integer',
        'bookable_type' => 'required|string',
        'percentage' => 'required|numeric|min:-100|max:100',
        'weekday' => 'required|string|in:sun,mon,tue,wed,thu,fri,sat',
        'starts_at' => 'required|date_format:"H:i:s"',
        'ends_at' => 'required|date_format:"H:i:s"',
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

        $this->setTable(config('rinvex.bookings.tables.prices'));
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
}