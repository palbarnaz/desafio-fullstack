<?php

namespace App\Services;


class MentorService
{

    public static function isValidEmailFormat($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function removeCpfMask(string $cpf): string
    {
        // Remove todos os pontos e traços do CPF
        return preg_replace('/[^0-9]/', '', $cpf);
    }

    public static function isValidCPF(string $cpf): bool
    {
        // Remove qualquer máscara do CPF (pontos, traços)
        $cpf =  self::removeCpfMask($cpf);

        // Verifica se o CPF tem 11 dígitos
        if (strlen($cpf) != 11) {
            return false;
        }

        // Verifica se todos os dígitos são iguais (casos como 111.111.111-11)
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        // Validação do primeiro dígito
        $sum = 0;
        for ($i = 0, $j = 10; $i < 9; $i++, $j--) {
            $sum += $cpf[$i] * $j;
        }

        $firstCheckDigit = ($sum * 10) % 11;
        if ($firstCheckDigit == 10) {
            $firstCheckDigit = 0;
        }

        if ($cpf[9] != $firstCheckDigit) {
            return false;
        }

        // Validação do segundo dígito
        $sum = 0;
        for ($i = 0, $j = 11; $i < 10; $i++, $j--) {
            $sum += $cpf[$i] * $j;
        }

        $secondCheckDigit = ($sum * 10) % 11;
        if ($secondCheckDigit == 10) {
            $secondCheckDigit = 0;
        }

        return $cpf[10] == $secondCheckDigit;
    }
}
