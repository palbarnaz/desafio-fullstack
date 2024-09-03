<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Mockery\Undefined;

class UserService
{


    public static function isStrongPassword($password)
    {
        $hasUpperCase = preg_match('@[A-Z]@', $password);
        $hasLowerCase = preg_match('@[a-z]@', $password);
        $hasNumber = preg_match('@[0-9]@', $password);
        $hasSpecialChar = preg_match('@[^\w]@', $password);

        return $hasUpperCase && $hasLowerCase && $hasNumber && $hasSpecialChar && strlen($password) >= 8;
    }

    public static function isValidEmailFormat($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}
