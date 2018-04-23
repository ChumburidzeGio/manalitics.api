<?php

use Illuminate\Database\Seeder;
use App\Models\Merchant;

class MerchantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->updateOrCreate('/(uber|HELPUBER)/', 'UBER');

        $this->updateOrCreate('/(SHOPIFY)/', 'SHOPIFY');

        $this->updateOrCreate('/(FACEBK)/', 'Facebook Ads');

        $this->updateOrCreate('/(Vomar)/', 'Vomar');

        $this->updateOrCreate('/(Albert Heijn|AH TO GO|AH Campus Diemen)/i', 'Albert Heijn');

        $this->updateOrCreate('/(Gall & Gall)\s+([0-9]+)/', 'Gall & Gall');

        $this->updateOrCreate('/(Wizz Air)/', 'Wizz Air');

        $this->updateOrCreate('/(DIRK VAN DEN BROEK|DIRK VDBROEK)/', 'DIRK VAN DEN BROEK');

        $this->updateOrCreate('/(PULL & BEAR)/', 'PULL & BEAR');

        $this->updateOrCreate('/(ETOS)\s+([0-9]+)/', 'Etos');

        $this->updateOrCreate('/(Pearle Opticiens)\s+([0-9]+)/', 'Pearle Opticiens');

        $this->updateOrCreate('/(BIEDRONKA)/', 'Biedronka');
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function updateOrCreate($pos_id, $title, $category_id = null, $tags = [])
    {
        $merchant = Merchant::updateOrCreate([
            'pos_id' => $pos_id
        ], compact('title', 'category_id', 'is_generic'));
    }
}
