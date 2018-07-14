<?php
namespace App\Consts;

class AllowedCurrencyCodes {
    const EUR = 'eur';
    const USD = 'usd';
    const PLN = 'pln';
    const GEL = 'gel';

    public static function all()
    {
        return [
            self::EUR,
            self::USD,
            self::PLN,
            self::GEL,
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