<?php

namespace App\Dto;

class LoyaltyPointsCancelDTO
{
    private string $cancellationReason;
    private string $transactionId;

    public function __construct(string $cancellationReason, string $transactionId){
        $this->cancellationReason = $cancellationReason;
        $this->transactionId = $transactionId;
    }

    public function getCancellationReason(): string
    {
        return $this->cancellationReason;
    }

    public function getTransactionId(): string
    {
        return $this->transactionId;
    }
}