<?php

namespace App\Dto;

class LoyaltyPointsWithdrawDTO
{
    private string $accountType;
    private string $accountId;
    private int $pointsAmount;
    private string $description;

    public function __construct(string $accountType, string $accountId, int $pointsAmount, string $description){
       $this->accountType = $accountType;
       $this->accountId = $accountId;
       $this->pointsAmount = $pointsAmount;
       $this->description = $description;
    }

    public function getAccountType(): string
    {
        return $this->accountType;
    }

    public function getAccountId(): string
    {
        return $this->accountId;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPointsAmount(): int
    {
        return $this->pointsAmount;
    }
}