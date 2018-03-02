<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type_id',              // The type of the property.
        'name',                 // The name of the property.
        'has_discounts',        // Indicates if any active discounts exist for the property.
        'description',          // The description of the property.
        'class',                // Property class if aplicable.
        'rooms',                // Amount of rooms at the property.
        'address1',             // The property's street address. {1 Infinite Loop}
        'address2',             // The property's additional street address (apt, suite, etc.). {Suite 100}
        'country_code',         // The two-letter country code corresponding to the property's country. {US}
        'city',                 // The city in which the property is located. {Cupertino}
        'zip',                  // The zip or postal code of the shop's address.
        'timezone',             // The name of the timezone the property is in. {(GMT-05:00) Eastern Time}
        'latitude',             // Geographic coordinate specifying the north/south location of a property. {45.427408}
        'longitude',            // Geographic coordinate specifying the east/west location of a property. {-75.68903}
        'currency',             // The three-letter code for the currency that the property accepts. {USD}
        'spoken_languages',     // Available spoken languages in the property.
        'primary_language',     // The properties primary spoken language.
        'has_wifi',             // Indicates if the property has the internet.
        'free_wifi',            // Indicates if the internet in the property is free.
        'has_parking',          // Indicates if the property has parking.
        'free_parking',         // Indicates if the parking in the property is free.
        'customer_email',       // The customer's service email. {customers@apple.com}
        'email',                // The contact email address for the shop. {steve@apple.com}
        'phone',                // The contact phone number for the shop.
        'setup_required',       // Indicates whether the shop has any outstanding setup steps or not.
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'type_id' => 'integer',
        'name' => 'string',
        'has_discounts' => 'boolean',
        'description' => 'string',
        'class' => 'integer',
        'rooms' => 'integer',
        'address1' => 'string',
        'address2' => 'string',
        'country_code' => 'string',
        'city' => 'string',
        'zip' => 'string',
        'timezone' => 'string',
        'latitude' => 'float',
        'longitude' => 'float',
        'currency' => 'string',
        'spoken_languages' => 'array',
        'primary_language' => 'string',
        'has_wifi' => 'boolean',
        'free_wifi' => 'boolean',
        'has_parking' => 'boolean',
        'free_parking' => 'boolean',
        'customer_email' => 'string',
        'email' => 'string',
        'phone' => 'string',
        'setup_required' => 'boolean',
    ];

    /**
     * Get the type record associated with the property.
     */
    public function type()
    {
        return $this->hasOne(PropertyType::class, 'id', 'type_id');
    }

    /**
     * Get the country record associated with the property.
     */
    public function country()
    {
        return $this->hasOne(Country::class, 'code', 'country_code');
    }
}