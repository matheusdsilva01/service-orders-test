<?php

namespace App\Domain;

class CommissionCalculator
{
    public function calculate(string $price): string
    {
        $priceInInt = $this->toInt($price);
        $rate = $this->getCommissionRate($priceInInt);

        $commissionInInt = intdiv(($priceInInt * $rate) + 50, 100);

        return $this->fromInt($commissionInInt);
    }

    private function getCommissionRate(int $price): int
    {
        if ($price > 10_000_000) {
            return 20;
        }

        if ($price > 1_000_000) {
            return 10;
        }

        return 5;
    }

    private function toInt(string $value): int
    {
        [$integer, $decimal] = array_pad(
            explode('.', $value, 2),
            2,
            ''
        );

        $decimal = str_pad($decimal, 3, '0');

        return ((int)$integer * 1000) + (int)$decimal;
    }

    private function fromInt(int $value): string
    {
        $integer = intdiv($value, 1000);
        $decimal = $value % 1000;

        return sprintf('%d.%03d', $integer, $decimal);
    }
}