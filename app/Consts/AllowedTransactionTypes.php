<?php
namespace App\Consts;

class AllowedTransactionTypes {
    const PAY_TERMINAL = "pay_terminal";
    const ONLINE_BANKING = "online_banking";
    const TRANSFER = "transfer";
    const DEBT_COLLECTION = "debt_collection";
    const ATM = "atm";
    const MISCELLANEOUS = "miscellaneous";

    public static function all()
    {
        return [
            self::PAY_TERMINAL,
            self::ONLINE_BANKING,
            self::TRANSFER,
            self::DEBT_COLLECTION,
            self::ATM,
            self::MISCELLANEOUS,
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