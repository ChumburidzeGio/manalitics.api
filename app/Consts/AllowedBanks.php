<?php
namespace App\Consts;

class AllowedBanks {
    const INGNL = 'ing.nl';
    const INGPL = 'ing.pl';
    const TBCBANK = 'tbcbank';

    public static function all()
    {
        return [
            self::INGNL,
            self::INGPL,
            self::TBCBANK,
        ];
    }

    public static function names()
    {
        return [
            self::INGNL => 'ING Bank',
            self::INGPL => 'ING',
            self::TBCBANK => 'TBC Bank',
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