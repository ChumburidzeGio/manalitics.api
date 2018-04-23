<?php

use Illuminate\Database\Seeder;
use App\Models\Category;

class MerchantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->create('Housing and Real-Estate', [
            'Electrical equipment and appliances',
            'Alarm and security',
            'Machinery and industry', //Maskin og industri
            'Power and heating',
            'Interior, furniture and lighting',
            'Hardware and building materials',
            'Telephone, TV and internet',
            'Handyman', //Håndverker
            'Housing companies and unions', //Borettslag og sameie
            'Flowers and plants',
            'Others',
        ]);

        $this->create('Health and Well Being', [
            'Optics',
            'Pharmacy and health food', //Apotek og helsekost
            'Personal care',
            'Healthcare',
            'Hairdresser',
            'Others',
        ]);

        $this->create('Recreation and Leisure', [
            'Travel',
            'Cinema, culture and events',
            'Exercise and leisure activities', //Trening og fritidsaktiviteter
            'Hotel and accommodation',
            'Restaurant, cafe and bar',
            'Others',
        ]);

        $this->create('Grocery', [
            'Food and household',
            'Snacks',
            'Others',
        ]);

        $this->create('Automobile and Transport', [
            'Tolls and parking',
            'Freight and goods', //Перевозка грузов и товаров
            'Taxi',
            'Public transport',
            'Customs and road tax',
            'Workshop, service and equipment', //Verksted, service og utstyr
            'Gas station', //Bensinstasjon
            'Car, boat and engine', //Bil, båt og motor
            'Others',
        ]);

        $this->create('Hobby and Knowledge', [
            'Newspapers and magazines',
            'Education and training',
            'Books, music and movies',
            'Toys, games and hobbies',
            'Charity',
            'Office supplies', //Kontorrekvisita
            'Animal husbandry', //Dyrehold
            'Art and photography',
            'Betting and gaming', //Tipping og pengespill
            'Others',
        ]);

        $this->create('Clothes and Equipment', [
            'Children',
            'Pleasure and sport',
            'Clothes, shoes and accessories',
            'Dry cleaning and repair',
            'Jewelry and watches',
            'Others',
        ]);

        $this->create('Other', [
            'Fees',
            'Duty Free', //Taxfree
            'Legal service',
            'Kindergarten',
            'Tax', //Skatt
        ]);

        $this->create('Cash and Credit', [
            'Cash withdrawal',
            'Credit card',
            'ATM', //Minibank
        ]);

        $this->create('Financial Services', [
            'Fees',
            'Savings',
            'Loan',
            'Insurance',
        ]);
    }
}