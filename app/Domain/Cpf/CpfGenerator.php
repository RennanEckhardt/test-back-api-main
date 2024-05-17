<?php

namespace App\Domain\Cpf;

class CpfGenerator
{
    public function generate(): string
    {
        $numbers = '';
        for ($i = 0; $i < 9; $i++) {
            $numbers .= mt_rand(0, 9);
        }

        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += $numbers[$i] * (10 - $i);
        }
        $remainder = $sum % 11;
        $firstVerifier = $remainder < 2 ? 0 : 11 - $remainder;

        $sum = 0;
        for ($i = 0; $i < 9; $i++) { 
            $sum += $numbers[$i] * (11 - $i);
        }
        $sum += $firstVerifier * 2;  
        $remainder = $sum % 11;
        $secondVerifier = $remainder < 2 ? 0 : 11 - $remainder;

        return $numbers . $firstVerifier . $secondVerifier;
    }
}
