<?php

namespace App\Utils;

class DayOfWeek
{
    public static function getDayOfWeek(int $number): string
    {
        switch ($number) {
            case 1:
                return 'Segunda-feira';
            case 2:
                return 'Terça-feira';
            case 3:
                return 'Quarta-feira';
            case 4:
                return 'Quinta-feira';
            case 5:
                return 'Sexta-feira';
            case 6:
                return 'Sábado';
            case 7:
                return 'Domingo';
            default:
                return 'Número inválido. Por favor, insira um número de 1 a 7.';
        }
    }
}
