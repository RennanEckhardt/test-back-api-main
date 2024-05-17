<?php

namespace App\Domain\Cpf;
use App\Exceptions\DataValidationException; 

/*
Original code to CPF validation:
https://gist.github.com/rafael-neri/ab3e58803a08cb4def059fce4e3c0e40
*/

class Cpf
{
    private const MAX_LENGTH = 11;

    private string $cpf;

    public function __construct(string $cpf)
    {
        $cpf = trim($cpf);

        if (!ctype_digit($cpf)) {
            throw new DataValidationException('The user cpf is not valid');
        }

        if (strlen($cpf) !== 11) { 
            throw new DataValidationException('The user cpf is not valid');
        }

        $this->cpf = $cpf;
    }

    public function isValid(): bool
    {
        $isValidLength = $this->isValidLength();
        
        $isValidNumber = $this->isValidNumber();

        return $isValidLength && $isValidNumber;
    }

    private function isValidLength(): bool
    {
        $cpf = $this->cpf;
        $cpf = preg_replace('/\D/', '', $cpf);
        return strlen($cpf) === 11;
    }

    private function isValidNumber(): bool
    {
        $cpf = $this->cpf;

        $cpf = preg_replace('/\D/', '', $cpf);
    
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }
    
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        return true;
    }
}
