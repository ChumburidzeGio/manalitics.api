<?php
namespace App\Consts;

class AllowedCurrencyCodes {
    const EUR = 'EUR';
    const USD = 'USD';
    const PLN = 'PLN';
    const GEL = 'GEL';

    public static function all()
    {
        return [
            self::EUR,
            self::USD,
            self::PLN,
            self::GEL,
        ];
    }

    public static function signs()
    {
        return [
            self::EUR => "€",
            self::USD => "$",
            self::PLN => "zl",
            self::GEL => "₾",
        ];
    }

    public static function signsRegex()
    {
        return [
            self::EUR => "€{amount}",
            self::USD => "\${amount}",
            self::PLN => "{amount} zl",
            self::GEL => "{amount} ლარი",
        ];
    }

    public static function getSignRegex($currency)
    {
        return array_get(self::signsRegex(), $currency);
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