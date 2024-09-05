<?php

namespace App\Utils;

class BodyMassIndexCalculator
{
    /**
     * Calcula o Índice de Massa Corporal (IMC) e retorna o status correspondente.
     *

     */
    public static function calculateIMC(float $height, float $weight): string
    {
        $imc = $weight / ($height * $height);

        if ($imc < 18.5) {
            return "Você está abaixo da faixa de peso ideal";
        } elseif ($imc > 24.99) {
            return "Você está acima da faixa de peso ideal";
        } else {
            return "Você está dentro da faixa de peso ideal";
        }
    }
}
