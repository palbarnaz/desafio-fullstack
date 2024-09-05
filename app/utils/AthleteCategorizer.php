<?php

namespace App\Utils;

class AthleteCategorizer
{
    /**
     * Determina a categoria de um atleta com base em sua idade e peso.
     *
     */
    public static function categorize(int $age, float $weight): string
    {
        // Verificação da categoria baseada na idade e peso
        if ($age <= 12) {
            return 'Infantil';
        } elseif ($age >= 13 && $age <= 16) {
            if ($weight <= 40) {
                return 'Juvenil leve';
            } else {
                return 'Juvenil pesado';
            }
        } elseif ($age >= 17 && $age <= 24) {
            if ($weight <= 45) {
                return 'Sênior leve';
            } elseif ($weight <= 60) {
                return 'Sênior médio';
            } else {
                return 'Sênior pesado';
            }
        } else {
            return 'Veterano';
        }
    }
}
