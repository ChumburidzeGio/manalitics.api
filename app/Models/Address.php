<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'address1',         // Address line 1 (Street address/PO Box/Company name).
        'address2',         // Address line 2 (Apartment/Suite/Unit/Building).
        'city',             // City/District/Suburb/Town/Village.
        'company',          // Company/Organization/Government.
        'country',          // State/County/Province/Region.
        'country_code',     // Two-letter country code. (For example, US.)
        'first_name',       // First name of the guest.
        'formatted',
        'formatted_area',   // Comma-separated list of city, province, and country.
        'last_name',        // Last name of the guest.
        'latitude',         // Latitude coordinate of the customer address.
        'longitude',        // Longitude coordinate of the customer address.
        'name',             // Name of the customer, based on first name + last name.
        'phone',            // Unique phone number for the customer. Formatted using E.164 standard. For example, +16135551111.
        'province',         // State/County/Province/Region.
        'province_code',    // Two-letter province or state code. For example, ON.
        'zip',              // Zip/Postal Code.
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'address1' => 'string',
        'address2' => 'string',
        'city' => 'string',
        'company' => 'string',
        'country' => 'string',
        'country_code' => 'string',
        'first_name' => 'string',
        'formatted' => 'string',
        'formatted_area' => 'string',
        'last_name' => 'string',
        'latitude' => 'float',
        'longitude' => 'float',
        'name' => 'string',
        'phone' => 'string',
        'province' => 'string',
        'province_code' => 'string',
        'zip' => 'string',
    ];
}