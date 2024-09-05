<?php

namespace App\Utils;

class TemperatureConverter
{
    /**
     * Converte a temperatura de Celsius para Fahrenheit.
     *
     */
    public static function celsiusToFahrenheit(float $celsius)
    {
        return ($celsius * 9 / 5) + 32;
    }
}
