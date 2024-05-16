<?php

namespace App\Domain\Cpf;

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
    
        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += (10 - $i) * intval($cpf[$i]);
        }
        $digit1 = ($sum * 10) % 11;
    
        $sum = 0;
        for ($i = 0; $i < 10; $i++) {
            $sum += (11 - $i) * intval($cpf[$i]);
        }
        $digit2 = ($sum * 10) % 11;
    
        return $cpf[9] == $digit1 && $cpf[10] == $digit2;
    }
}
