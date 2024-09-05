<?php

namespace App\Utils;


class NumberChecker
{
    public static function checkNumber(float $number): string
    {
        if ($number > 0) {
            return 'O número é positivo.';
        } elseif ($number < 0) {
            return 'O número é negativo.';
        } else {
            return 'O número é zero.';
        }
    }
}
