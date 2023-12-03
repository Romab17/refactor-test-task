<?php

namespace App\Service;

use App\Models\LoyaltyAccount;
use App\Utils\LoggerService;

class AccountService
{
    private LoggerService $loggerService;
    public function __construct(LoggerService $loggerService) {
        $this->loggerService = $loggerService;
    }
    public function getBalance(string $type, string $id): ?float
    {
        if (($type == 'phone' || $type == 'card' || $type == 'email') && $id != '') {
            if ($account = LoyaltyAccount::where($type, '=', $id)->first()) {
                return $account->getBalance();
            } else {
                return null;
            }
        } else {
            $this->loggerService->logInfo('Wrong parameters trying to get balance by account id: ' . $id);
        }
    }
}