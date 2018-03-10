<?php

use Illuminate\Database\Seeder;
use App\Models\Property\PropertyType;

class PropertyTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PropertyType::updateOrCreate([
            'slug' => str_slug('Apartment')
        ], [
            'en' => [
                'name' => 'Apartment',
                'description' => 'Furnished, independent accommodations available for short- and long-term rental',
            ]
        ]);

        PropertyType::updateOrCreate([
            'slug' => str_slug('Hotel')
        ], [
            'en' => [
                'name' => 'Hotel',
                'description' => 'Accommodations for travelers often with restaurants, meeting rooms and other guest services',
            ]
        ]);

        PropertyType::updateOrCreate([
            'slug' => str_slug('Vacation Home')
        ], [
            'en' => [
                'name' => 'Vacation Home',
                'description' => 'Freestanding home with private, external entrance and rented specifically for vacation',
            ]
        ]);

        PropertyType::updateOrCreate([
            'slug' => str_slug('Guesthouse')
        ], [
            'en' => [
                'name' => 'Guesthouse',
                'description' => 'Private home with separate living facilities for host and guest',
            ]
        ]);

        PropertyType::updateOrCreate([
            'slug' => str_slug('Bed and Breakfast')
        ], [
            'en' => [
                'name' => 'Bed and Breakfast',
                'description' => 'Private home offering overnight stays and breakfast',
            ]
        ]);
    }
}
