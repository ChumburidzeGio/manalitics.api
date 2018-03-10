<?php

use Illuminate\Database\Seeder;
use App\Models\RoomType;

class CreateRoomTypesTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->updateOrCreate('Apartment');
        $this->updateOrCreate('Quadruple');
        $this->updateOrCreate('Suite');
        $this->updateOrCreate('Triple');
        $this->updateOrCreate('Twin');
        $this->updateOrCreate('Double');
        $this->updateOrCreate('Single');
        $this->updateOrCreate('Studio');
        $this->updateOrCreate('Family');
        $this->updateOrCreate('Dormitory room');
        $this->updateOrCreate('Bed in Dormitory');
        $this->updateOrCreate('Bungalow');
        $this->updateOrCreate('Chalet');
        $this->updateOrCreate('Holiday home');
        $this->updateOrCreate('Villa');
        $this->updateOrCreate('Mobile home');
        $this->updateOrCreate('Tent');
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function updateOrCreate($name_en, $description_en = '', $name_ka = '', $description_ka = '')
    {
        RoomType::updateOrCreate([
            'slug' => str_slug($name_en)
        ], [
            'en' => [
                'name' => $name_en,
                'description' => $description_en,
            ],
            'ka' => [
                'name' => $name_ka,
                'description' => $description_ka,
            ],
        ]);
    }
}
