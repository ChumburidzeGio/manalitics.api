<?php
namespace App\Normalizers;

// https://fixer.io/documentation

class MoneyNormalizer {

    public static function normalize($amount)
    {
        return number_format($amount, 2);
    }

    public static function toCash($amount)
    {
        return self::normalize($amount / 100);
    }

}