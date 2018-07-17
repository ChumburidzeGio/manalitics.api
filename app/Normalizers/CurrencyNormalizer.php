<?php
namespace App\Normalizers;

use App\Consts\AllowedCurrencyCodes;
use Spatie\Regex\Regex;

// https://fixer.io/documentation

class CurrencyNormalizer {

    protected $base;

    protected $amount;

    protected $currency;

    protected $result;
    
    public function __construct($amount, $currency = null)
    {
        $this->set('base', config('app.currency'));

        $this->set('amount', $amount);

        $this->set('currency', $currency ?? config('app.currency'));

        $this->normalize();

        return $this;
    }
    
    private function normalize()
    {
        if($this->currency === $this->base) {
            return $this->set('result', $this->amount);
        }

        $rate = $this->getExchangeRate();

        return $this->set('result', $this->amount * $rate);
    }

    public function set($key, $value)
    {
        $this->{$key} = $value;

        return $this;
    }

    public function shape()
    {
        $amount = MoneyNormalizer::toCash($this->result);

        $signRegex = AllowedCurrencyCodes::getSign($this->base);

        $isNegative = starts_with($amount, '-');

        $formatedAmount = str_replace('-', '', $amount);

        $shaped = Regex::replace('/{amount}/', $formatedAmount, $signRegex)->result();

        $result = $isNegative ? "-{$shaped}" : $shaped;

        return $this->set('result', $result);
    }

    public function result()
    {
        return $this->result;
    }

    public function getExchangeRate()
    {
        $path = resource_path("/data/exchange.json");

        $rates = json_decode(file_get_contents($path));

        if($rates->base === $this->currency)
        {
            return $rates->rates->{$this->base};
        }

        if($rates->base === $this->base)
        {
            return 1 / $rates->rates->{$this->currency};
        }

        $fromRate = $rates->rates->{$this->currency};

        $toRate = $rates->rates->{$this->base};

        return $toRate / $fromRate;
    }
}