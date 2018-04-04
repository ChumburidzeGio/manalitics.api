<?php

use Illuminate\Database\Seeder;
use App\Models\Merchant;

class CreateRoomTypesTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->updateOrCreate('/(uber)(.*)(help)(?=.*)/gi', 'UBER', 'transportation', true);

        $this->updateOrCreate('/(SHOPIFY.COM|SHOPIFYCOM)/g', 'SHOPIFY', 'business/hosting', true);

        $this->updateOrCreate('/(FACEBK \*)([A-Z0-9]+)/g', 'Facebook Ads', 'business/advertising', true);

        $this->updateOrCreate('/(Albert Heijn|AH TO GO)\s+([0-9]+)/g', 'Albert Heijn', 'basic', true, [
            "supermarket",
            "grocery_or_supermarket",
            "store",
            "food",
            "point_of_interest",
            "establishment"
        ]);

        $this->updateOrCreate('/(Gall & Gall)\s+([0-9]+)/g', 'Gall & Gall', 'healthnbeauty', true, [
            "liquor_store",
            "store",
            "point_of_interest",
            "establishment"
        ]);

        $this->updateOrCreate('/(Wizz Air Hu)\s+([A-Z_0-9]+)/g', 'Wizz Air', 'travel', true);

        $this->updateOrCreate('/(DIRK VAN DEN BROEK)/g', 'DIRK VAN DEN BROEK', 'basic', true, [
            "supermarket",
            "grocery_or_supermarket",
            "store",
            "food",
            "point_of_interest",
            "establishment"
        ]);

        $this->updateOrCreate('/(PULL & BEAR)/g', 'PULL & BEAR', 'clothing', true, [
            "clothing_store",
            "store",
            "point_of_interest",
            "establishment"
        ]);

        $this->updateOrCreate('/(ETOS)\s+([0-9]+)/g', 'Etos', 'healthnbeauty', true, [
            "store",
            "health",
            "point_of_interest",
            "establishment"
        ]);

        $this->updateOrCreate('/(Pearle Opticiens)\s+([0-9]+)/g', 'Pearle Opticiens', 'healthnbeauty', true, [
            "store",
            "health",
            "point_of_interest",
            "establishment"
        ]);
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function updateOrCreate($pos_id, $title, $category_id, $is_generic, $tags)
    {
        $merchant = Merchant::updateOrCreate([
            'pos_id' => $pos_id
        ], compact('title', 'category_id', 'is_generic'));
    }
}
