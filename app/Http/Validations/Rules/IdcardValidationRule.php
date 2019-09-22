<?php

namespace App\Http\Validations\Rules;

use Illuminate\Contracts\Validation\Rule;

class IdcardValidationRule implements Rule
{
    public function passes($attribute, $value)
    {
        $identityCardNumber = str_replace(' ', '', $value);
        $identityCardNumber = str_replace('-', '', $identityCardNumber);
        $identityCardNumber = str_replace('_', '', $identityCardNumber);
        $identityCardNumber = strtoupper($identityCardNumber);
        $regionCode = (int) substr($identityCardNumber, 0, 6);
        return ($regionCode >= 110000
         && $regionCode <= 820000
         && checkdate(
             intval(substr($identityCardNumber, 10, 2)),
             intval(substr($identityCardNumber, 12, 2)),
             intval(substr($identityCardNumber, 6, 4))
            )
         && $this->validateCheckCode($identityCardNumber)
        );
    }
    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '身份证格式不正确';
    }

    public function validateCheckCode($identityCardNumber): bool
    {
        // Init
        $index = $sum = 0;
        // Calculation $sum
        for ($index; $index < 17; $index++) {
            $sum += ((1 << (17 - $index)) % 11) * intval(substr($identityCardNumber, $index, 1));
        }
        // Calculation $quotiety
        $quotiety = (12 - ($sum % 11)) % 11;
        if ($quotiety < 10) {
            return intval(substr($identityCardNumber, 17, 1)) === $quotiety;
        }
        return $quotiety === 10;
    }
}