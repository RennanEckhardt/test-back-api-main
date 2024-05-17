<?php

namespace App\Domain\User;

use App\Domain\Cpf\Cpf;
use App\Domain\LoanEligibility\UserLoanEligibility;
use App\Exceptions\DataValidationException;

class UserDataValidator implements UserDataValidatorInterface
{
    private const ID_MAX_LEGTH = 36;
    private const NAME_MAX_LEGTH = 100;
    private const EMAIL_MAX_LEGTH = 100;

    private const UUID_REGEX = '/[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}/';

    public function validateId(string $id): void
    {
        if (!preg_match(self::UUID_REGEX, $id)) {
            throw new DataValidationException('The user id is not valid');
        }
    }

    public function validateName(string $name): void
    {
        if (empty(trim($name))) {
            throw new DataValidationException('The user name cannot be empty');
        } elseif (strlen(trim($name)) > self::NAME_MAX_LEGTH) {
            throw new DataValidationException('The user name exceeds the max length');
        }
    }

    public function validateEmail(string $email): void
{
    if (empty(trim($email))) {
        throw new DataValidationException('The user email cannot be empty');
    } elseif (strlen(trim($email)) > self::EMAIL_MAX_LEGTH) { // Verifica o comprimento primeiro
        throw new DataValidationException('The user email exceeds the max length');
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) { // Depois verifica o formato
        throw new DataValidationException('The user email is not valid');
    } 
}

    public function validateCpf(string $cpf): void
    {
        $trimmedCpf = trim($cpf);
        if (empty($trimmedCpf)) {
            throw new DataValidationException('The user cpf cannot be empty');
        }
        if (!(new Cpf($trimmedCpf))->isValid()) {
            throw new DataValidationException('The user cpf is not valid');
        }
    }

    public function validateDateCreation(string $dateCreation): void
    {
        if (empty(trim($dateCreation))) {
            throw new DataValidationException('The user date creation cannot be empty');
        } elseif (!\DateTime::createFromFormat('Y-m-d H:i:s', $dateCreation)) {
            throw new DataValidationException('The user date creation is not in a valid format');
        }
    }

    public function validateDateEdition(string $dateEdition): void
    {
        if (empty(trim($dateEdition))) {
            throw new DataValidationException('The user date edition cannot be empty');
        } elseif (!\DateTime::createFromFormat('Y-m-d H:i:s', $dateEdition)) {
            throw new DataValidationException('The user date edition is not in a valid format');
        }
    }

    public function isEligibleForLoan(User $user): bool
    {
        $eligibilityChecker = new UserLoanEligibility();
        return $eligibilityChecker->isEligible($user);
    }
    
}
