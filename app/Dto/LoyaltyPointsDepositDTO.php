<?php

namespace App\Dto;

class LoyaltyPointsDepositDTO
{
    private string $accountType;
    private string $accountId;
    private string $loyaltyPointsRule;
    private string $description;
    private string $paymentId;
    private string $paymentAmount;
    private string $paymentTime;

    public function __construct(string $accountType, string $accountId, string $loyaltyPointsRule, string $description,
    string $paymentId, string $paymentAmount, string $paymentTime){
        $this->accountType = $accountType;
        $this->accountId = $accountId;
        $this->loyaltyPointsRule = $loyaltyPointsRule;
        $this->description = $description;
        $this->paymentId = $paymentId;
        $this->paymentAmount = $paymentAmount;
        $this->paymentTime = $paymentTime;
    }

    public function getAccountType(): string
    {
        return $this->accountType;
    }

    public function getAccountId(): string
    {
        return $this->accountId;
    }

    public function getLoyaltyPointsRule(): string
    {
        return $this->loyaltyPointsRule;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPaymentId(): string
    {
        return $this->paymentId;
    }

    public function getPaymentAmount(): string
    {
        return $this->paymentAmount;
    }

    public function getPaymentTime(): string
    {
        return $this->paymentTime;
    }
}