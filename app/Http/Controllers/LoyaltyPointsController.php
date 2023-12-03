<?php

namespace App\Http\Controllers;

use App\Dto\LoyaltyPointsCancelDTO;
use App\Dto\LoyaltyPointsDepositDTO;
use App\Dto\LoyaltyPointsWithdrawDTO;
use App\Service\LoyaltyPointsService;
use Illuminate\Http\JsonResponse;

class LoyaltyPointsController extends Controller
{
    private LoyaltyPointsService $loyaltyPointsService;

    public function __construct(LoyaltyPointsService $loyaltyPointsService)
    {
        $this->loyaltyPointsService = $loyaltyPointsService;
    }

    public function deposit(LoyaltyPointsDepositDTO $loyaltyPointsDepositDTO): JsonResponse
    {
        $loyaltyPointsTransaction = $this->loyaltyPointsService->deposit($loyaltyPointsDepositDTO);

        if (is_null($loyaltyPointsTransaction)) {
            return response()->json(['message' => 'ERR_DEPOSITING_POINTS'], 400);
        } else {
            return response()->json(['message' => 'OK'], 200);
        }
    }

    public function cancel(LoyaltyPointsCancelDTO $loyaltyPointsCancelDTO): JsonResponse
    {
        $transaction = $this->loyaltyPointsService->cancel($loyaltyPointsCancelDTO);

        if (is_null($transaction)) {
            return response()->json(['message' => 'ERR_CANCELLATION_POINTS'], 400);
        } else {
            return response()->json(['message' => 'OK'], 200);
        }
    }

    public function withdraw(LoyaltyPointsWithdrawDTO $loyaltyPointsWithdrawDTO): JsonResponse
    {
        $transaction = $this->loyaltyPointsService->withdraw($loyaltyPointsWithdrawDTO);

        if (is_null($transaction)) {
            return response()->json(['message' => 'ERR_WITHDRAW_POINTS'], 400);
        } else {
            return response()->json(['message' => 'OK'], 200);
        }
    }
}
