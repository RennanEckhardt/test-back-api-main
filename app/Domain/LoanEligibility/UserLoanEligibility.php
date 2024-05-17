<?php

namespace App\Domain\LoanEligibility;

use Carbon\Carbon;
use App\Domain\User\User;

class UserLoanEligibility
{
    public function isEligible(User $user): bool
    {
        $createdAt = Carbon::parse($user->getDateCreation());
        $sixMonthsAgo = Carbon::now()->subMonths(6);
        return $createdAt->lt($sixMonthsAgo);
    }
}
