<?php
namespace App\Consts;

class AllowedTimezones {
    const CET = 'cet';

    public static function all()
    {
        return [
            self::CET,
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