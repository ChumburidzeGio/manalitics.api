<?php
namespace App\Consts;

class AllowedCategories {
    const AUTO_TRANSPORT = 'auto_and_transport';
    const AUTO_TRANSPORT__CAR_RENTAL = 'auto_and_transport.car_rental';
    const AUTO_TRANSPORT__GAS_FUEL = 'auto_and_transport.gas_and_fuel';
    const AUTO_TRANSPORT__PARKING = 'auto_and_transport.parking';
    const AUTO_TRANSPORT__PUBLIC_TRANSPORTATION = 'auto_and_transport.public_transportation';
    const AUTO_TRANSPORT__SERVICE_PARTS = 'auto_and_transport.service_and_parts';
    const AUTO_TRANSPORT__TAXI = 'auto_and_transport.taxi';

    const BILLS_UTILITIES = "bills_and_utilities";
    const BILLS_UTILITIES__INTERNET = "bills_and_utilities.internet";
    const BILLS_UTILITIES__PHONE = "bills_and_utilities.phone";
    const BILLS_UTILITIES__TV = "bills_and_utilities.television";
    const BILLS_UTILITIES__UTILITIES = "bills_and_utilities.utilities";

    const BUSINESS_SERVICES = "business_services";
    const BUSINESS_SERVICES__ADVERTISING = "business_services.advertising";
    const BUSINESS_SERVICES__OFFICE_SUPPLIES = "business_services.office_supplies";
    const BUSINESS_SERVICES__SHIPPING = "business_services.shipping";

    const EDUCATION = "education";
    const EDUCATION__BOOKS_SUPPLIES = "education.books_and_supplies";
    const EDUCATION__STUDENT_LOAN = "education.student_loan";
    const EDUCATION__TUITION = "education.tuition";

    const ENTERTAINMENT = "entertainment";
    const ENTERTAINMENT__AMUSEMENT = "entertainment.amusement";
    const ENTERTAINMENT__ARTS = "entertainment.arts";
    const ENTERTAINMENT__GAMES = "entertainment.games";
    const ENTERTAINMENT__MOVIES_MUSIC = "entertainment.movies_and_music";
    const ENTERTAINMENT__NEWSPAPER = "entertainment.newspapers_and_magazines";

    const FEES_CHARGES = "fees_and_charges";
    const FEES_CHARGES__PROVIDER_FEE = "fees_and_charges.provider_fee";
    const FEES_CHARGES__LOAN = "fees_and_charges.loans";
    const FEES_CHARGES__SERVICE_FEE = "fees_and_charges.service_fee";
    const FEES_CHARGES__TAXES = "fees_and_charges.taxes";

    const FOOD_DINING = "food_and_dining";
    const FOOD_DINING__ALCOHOL_BARS = "food_and_dining.alcohol_and_bars";
    const FOOD_DINING__CAFES_RESTAURANTS = "food_and_dining.cafes_and_restaurants";
    const FOOD_DINING__GROCERIES = "food_and_dining.groceries";

    const GIFTS_DONATIONS = "gifts_and_donations";
    const GIFTS_DONATIONS__CHARITY = "gifts_and_donations.charity";
    const GIFTS_DONATIONS__GIFTS = "gifts_and_donations.gifts";

    const HEALTH_FITNESS = "health_and_fitness";
    const HEALTH_FITNESS__DOCTOR = "health_and_fitness.doctor";
    const HEALTH_FITNESS__PERSONAL_CARE = "health_and_fitness.personal_care";
    const HEALTH_FITNESS__PHARMACY = "health_and_fitness.pharmacy";
    const HEALTH_FITNESS__SPORTS = "health_and_fitness.sports";
    const HEALTH_FITNESS__WELLNESS = "health_and_fitness.wellness";

    const HOME = "home";
    const HOME__HOME_IMPROVEMENT = "home.home_improvement";
    const HOME__HOME_SERVICES = "home.home_services";
    const HOME__HOME_SUPPLIES = "home.home_supplies";
    const HOME__MORTGAGE_RENT = "home.mortgage_and_rent";

    const INCOME = "income";
    const INCOME__BONUS = "income.bonus";
    const INCOME__INVESTMENT_INCOME = "income.investment_income";
    const INCOME__PAYCHECK = "income.paycheck";

    const INSURANCE = "insurance";
    const INSURANCE__CAR = "insurance.car_insurance";
    const INSURANCE__HEALTH = "insurance.health_insurance";
    const INSURANCE__LIFE = "insurance.life_insurance";
    const INSURANCE__PROPERTY = "insurance.property_insurance";

    const KIDS = "kids";
    const KIDS__ALLOWANCE = "kids.allowance";
    const KIDS__BABYSITTER_DAYCARE = "kids.babysitter_and_daycare";
    const KIDS__SUPPLIES = "kids.baby_supplies";
    const KIDS__CHILD_SUPPORT = "kids.child_support";
    const KIDS__KIDS_ACTIVITY = "kids.kids_activities";
    const KIDS__TOYS = "kids.toys";

    const PETS = "pets";
    const PETS__FOOD_SUPPLIES = "pets.pet_food_and_supplies";
    const PETS__GROOMING = "pets.pet_grooming";
    const PETS__VETERINARY = "pets.veterinary";

    const SHOPPING = "shopping";
    const SHOPPING__CLOTHING = "shopping.clothing";
    const SHOPPING__ELECTRONICS_SOFTWARE = "shopping.electronics_and_software";
    const SHOPPING__SPORTING_GOOD = "shopping.sporting_goods";

    const TRANSFER = "transfer";

    const TRAVEL = "travel";
    const TRAVEL_HOTEL = "travel.hotel";
    const TRAVEL_TRANSPORTATION = "travel.transportation";
    const TRAVEL_VACATION = "travel.vacation";

    const UNCATEGORIZED = "uncategorized";

    public static function all()
    {
        return [
            self::AUTO_TRANSPORT => [
                self::AUTO_TRANSPORT__CAR_RENTAL,
                self::AUTO_TRANSPORT__GAS_FUEL,
                self::AUTO_TRANSPORT__PARKING,
                self::AUTO_TRANSPORT__PUBLIC_TRANSPORTATION,
                self::AUTO_TRANSPORT__SERVICE_PARTS,
                self::AUTO_TRANSPORT__TAXI,
            ],
            self::BILLS_UTILITIES => [
                self::BILLS_UTILITIES__INTERNET,
                self::BILLS_UTILITIES__PHONE,
                self::AUTO_TRANSPORT__PARKING,
                self::BILLS_UTILITIES__TV,
                self::BILLS_UTILITIES__UTILITIES,
            ],
            self::BUSINESS_SERVICES => [
                self::BUSINESS_SERVICES__ADVERTISING,
                self::BUSINESS_SERVICES__OFFICE_SUPPLIES,
                self::BUSINESS_SERVICES__SHIPPING,
            ],
            self::EDUCATION => [
                self::EDUCATION__BOOKS_SUPPLIES,
                self::EDUCATION__STUDENT_LOAN,
                self::EDUCATION__TUITION,
            ],
            self::ENTERTAINMENT => [
                self::ENTERTAINMENT__AMUSEMENT,
                self::ENTERTAINMENT__ARTS,
                self::ENTERTAINMENT__GAMES,
                self::ENTERTAINMENT__MOVIES_MUSIC,
                self::ENTERTAINMENT__NEWSPAPER,
            ],
            self::FEES_CHARGES => [
                self::FEES_CHARGES__PROVIDER_FEE,
                self::FEES_CHARGES__LOAN,
                self::FEES_CHARGES__SERVICE_FEE,
                self::FEES_CHARGES__TAXES,
            ],
            self::FOOD_DINING => [
                self::FOOD_DINING__ALCOHOL_BARS,
                self::FOOD_DINING__CAFES_RESTAURANTS,
                self::FOOD_DINING__GROCERIES,
            ],
            self::GIFTS_DONATIONS => [
                self::GIFTS_DONATIONS__CHARITY,
                self::GIFTS_DONATIONS__GIFTS,
            ],
            self::HEALTH_FITNESS => [
                self::HEALTH_FITNESS__DOCTOR,
                self::HEALTH_FITNESS__PERSONAL_CARE,
                self::HEALTH_FITNESS__PHARMACY,
                self::HEALTH_FITNESS__SPORTS,
                self::HEALTH_FITNESS__WELLNESS,
            ],
            self::HOME => [
                self::HOME__HOME_IMPROVEMENT,
                self::HOME__HOME_SERVICES,
                self::HOME__HOME_SUPPLIES,
                self::HOME__MORTGAGE_RENT,
            ],
            self::INCOME => [
                self::INCOME__BONUS,
                self::INCOME__INVESTMENT_INCOME,
                self::INCOME__PAYCHECK,
            ],
            self::INSURANCE => [
                self::INSURANCE__CAR,
                self::INSURANCE__HEALTH,
                self::INSURANCE__LIFE,
                self::INSURANCE__PROPERTY,
            ],
            self::KIDS => [
                self::KIDS__ALLOWANCE,
                self::KIDS__BABYSITTER_DAYCARE,
                self::KIDS__SUPPLIES,
                self::KIDS__CHILD_SUPPORT,
                self::KIDS__KIDS_ACTIVITY,
                self::KIDS__TOYS,
            ],
            self::PETS => [
                self::PETS__FOOD_SUPPLIES,
                self::PETS__GROOMING,
                self::PETS__VETERINARY,
            ],
            self::SHOPPING => [
                self::SHOPPING__CLOTHING,
                self::SHOPPING__ELECTRONICS_SOFTWARE,
                self::SHOPPING__SPORTING_GOOD,
            ],
            self::TRANSFER => [],
            self::TRAVEL => [
                self::TRAVEL_HOTEL,
                self::TRAVEL_TRANSPORTATION,
                self::TRAVEL_VACATION,
            ],
            self::UNCATEGORIZED => [],
        ];
    }

    public static function names()
    {
        return [
            self::AUTO_TRANSPORT => [
                'label' => 'Auto & Transport',
                'options' => [
                    self::AUTO_TRANSPORT__CAR_RENTAL => 'Car rental',
                    self::AUTO_TRANSPORT__GAS_FUEL => 'Gas and fuel',
                    self::AUTO_TRANSPORT__PARKING => 'Parking',
                    self::AUTO_TRANSPORT__PUBLIC_TRANSPORTATION => 'Public transportation',
                    self::AUTO_TRANSPORT__SERVICE_PARTS => 'Service and parts',
                    self::AUTO_TRANSPORT__TAXI => 'Taxi',
                ]
            ],
            self::BILLS_UTILITIES => [
                'label' => 'Bills and utilities',
                'options' => [
                    self::BILLS_UTILITIES__INTERNET => 'Internet',
                    self::BILLS_UTILITIES__PHONE => 'Phone',
                    self::BILLS_UTILITIES__TV => 'Television',
                    self::BILLS_UTILITIES__UTILITIES => 'Utilities',
                ],
            ],
            self::BUSINESS_SERVICES => [
                'label' => 'Business services',
                'options' => [
                    self::BUSINESS_SERVICES__ADVERTISING => 'Advertising',
                    self::BUSINESS_SERVICES__OFFICE_SUPPLIES => 'Office supplies',
                    self::BUSINESS_SERVICES__SHIPPING => 'Shipping',
                ],
            ],
            self::EDUCATION => [
                'label' => 'Education',
                'options' => [
                    self::EDUCATION__BOOKS_SUPPLIES => 'Books and supplies',
                    self::EDUCATION__STUDENT_LOAN => 'Student loan',
                    self::EDUCATION__TUITION => 'Tuition',
                ],
            ],
            self::ENTERTAINMENT => [
                'label' => 'Entertainment',
                'options' => [
                    self::ENTERTAINMENT__AMUSEMENT => 'Amusement',
                    self::ENTERTAINMENT__ARTS => 'Arts',
                    self::ENTERTAINMENT__GAMES => 'Games',
                    self::ENTERTAINMENT__MOVIES_MUSIC => 'Movies and music',
                    self::ENTERTAINMENT__NEWSPAPER => 'Newspapers and magazines',
                ],
            ],
            self::FEES_CHARGES => [
                'label' => 'Fees and charges',
                'options' => [
                    self::FEES_CHARGES__PROVIDER_FEE => 'Provider fee',
                    self::FEES_CHARGES__LOAN => 'Loans',
                    self::FEES_CHARGES__SERVICE_FEE => 'Service fee',
                    self::FEES_CHARGES__TAXES => 'Taxes',
                ],
            ],
            self::FOOD_DINING => [
                'label' => 'Food and dining',
                'options' => [
                    self::FOOD_DINING__ALCOHOL_BARS => 'Alcohol and bars',
                    self::FOOD_DINING__CAFES_RESTAURANTS => 'Cafes and restaurants',
                    self::FOOD_DINING__GROCERIES => 'Groceries',
                ],
            ],
            self::GIFTS_DONATIONS => [
                'label' => 'Gifts and donations',
                'options' => [
                    self::GIFTS_DONATIONS__CHARITY => 'Charity',
                    self::GIFTS_DONATIONS__GIFTS => 'Gifts',
                ],
            ],
            self::HEALTH_FITNESS => [
                'label' => 'Health and fitness',
                'options' => [
                    self::HEALTH_FITNESS__DOCTOR => 'Doctor',
                    self::HEALTH_FITNESS__PERSONAL_CARE => 'Personal care',
                    self::HEALTH_FITNESS__PHARMACY => 'Pharmacy',
                    self::HEALTH_FITNESS__SPORTS => 'Sports',
                    self::HEALTH_FITNESS__WELLNESS => 'Wellness',
                ],
            ],
            self::HOME => [
                'label' => 'Home',
                'options' => [
                    self::HOME__HOME_IMPROVEMENT => 'Home improvement',
                    self::HOME__HOME_SERVICES => 'Home services',
                    self::HOME__HOME_SUPPLIES => 'Home supplies',
                    self::HOME__MORTGAGE_RENT => 'Mortgage and rent',
                ],
            ],
            self::INCOME => [
                'label' => 'Income',
                'options' => [
                    self::INCOME__BONUS => 'Bonus',
                    self::INCOME__INVESTMENT_INCOME => 'Investment income',
                    self::INCOME__PAYCHECK => 'Paycheck',
                ],
            ],
            self::INSURANCE => [
                'label' => 'Insurance',
                'options' => [
                    self::INSURANCE__CAR => 'Car insurance',
                    self::INSURANCE__HEALTH => 'Health insurance',
                    self::INSURANCE__LIFE => 'Life insurance',
                    self::INSURANCE__PROPERTY => 'Property insurance',
                ],
            ],
            self::KIDS => [
                'label' => 'Kids',
                'options' => [
                    self::KIDS__ALLOWANCE => 'Allowance',
                    self::KIDS__BABYSITTER_DAYCARE => 'Babysitter and daycare',
                    self::KIDS__SUPPLIES => 'Baby supplies',
                    self::KIDS__CHILD_SUPPORT => 'Child support',
                    self::KIDS__KIDS_ACTIVITY => 'Kids activities',
                    self::KIDS__TOYS => 'Toys',
                ],
            ],
            self::PETS => [
                'label' => 'Pets',
                'options' => [
                    self::PETS__FOOD_SUPPLIES => 'Pet food and supplies',
                    self::PETS__GROOMING => 'Pet grooming',
                    self::PETS__VETERINARY => 'Veterinary',
                ],
            ],
            self::SHOPPING => [
                'label' => 'Shopping',
                'options' => [
                    self::SHOPPING__CLOTHING => 'Clothing',
                    self::SHOPPING__ELECTRONICS_SOFTWARE => 'Electronics and software',
                    self::SHOPPING__SPORTING_GOOD => 'Sporting goods',
                ],
            ],
            self::TRANSFER => [
                'label' => 'Transfer',
            ],
            self::TRAVEL => [
                'label' => 'Travel',
                'options' => [
                    self::TRAVEL_HOTEL => 'Hotel',
                    self::TRAVEL_TRANSPORTATION => 'Transportation',
                    self::TRAVEL_VACATION => 'Vacation',
                ],
            ],
            self::UNCATEGORIZED => [
                'label' => 'Uncategorized',
            ],
        ];
    }
    
    public static function is_valid($type)
    {
        return in_array($type, self::all());
    }

    public static function is_invalid($type)
    {
        return !self::is_valid($type);
    }
}