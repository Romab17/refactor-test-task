<?php

namespace App\Service;

use App\Dto\LoyaltyPointsCancelDTO;
use App\Dto\LoyaltyPointsDepositDTO;
use App\Dto\LoyaltyPointsWithdrawDTO;
use App\Mail\LoyaltyPointsReceived;
use App\Models\LoyaltyAccount;
use App\Models\LoyaltyPointsTransaction;
use App\Utils\LoggerService;
use App\Utils\MailService;
use Exception;

class LoyaltyPointsService
{
    private LoggerService $loggerService;
    private MailService $mailService;
    public function __construct(LoggerService $loggerService, MailService $mailService) {
        $this->loggerService = $loggerService;
        $this->mailService = $mailService;
    }

    public function deposit(LoyaltyPointsDepositDTO $loyaltyPointsDepositDTO): ?LoyaltyPointsTransaction
    {
        $this->loggerService->logInfo('Deposit transaction for accountId: ' . $loyaltyPointsDepositDTO->getAccountId());

        $type = $loyaltyPointsDepositDTO->getPaymentType();
        $id = $loyaltyPointsDepositDTO->getAccountId();
        if (($type === 'phone' || $type === 'card' || $type === 'email') && $id !== '') {
            if ($account = LoyaltyAccount::where($type, '=', $id)->first()) {
                if ($account->active) {
                    $transaction =  LoyaltyPointsTransaction::performPaymentLoyaltyPoints($account->id, $loyaltyPointsDepositDTO->getLoyaltyPointsRule(),
                        $loyaltyPointsDepositDTO->getDescription(), $loyaltyPointsDepositDTO->getPaymentId(),
                        $loyaltyPointsDepositDTO->getPaymentAmount(), $loyaltyPointsDepositDTO->getPaymentTime());
                    $this->loggerService->logInfo($transaction);
                    if ($account->email != '' && $account->email_notification) {
                        $mail = new LoyaltyPointsReceived($transaction->points_amount, $account->getBalance());
                        $this->mailService->sendMail($account, $mail);
                    }
                    if ($account->phone != '' && $account->phone_notification) {
                        $this->loggerService->logInfo('You received' . $transaction->points_amount . 'Your balance' . $account->getBalance());
                    }
                    return $transaction;
                } else {
                    $this->loggerService->logInfo('Account is not active');
                    return null;
                }
            } else {
                $this->loggerService->logInfo('Account is not found');
                return null;
            }
        } else {
            $this->loggerService->logInfo('Wrong account parameters for accountId: ' . $loyaltyPointsDepositDTO->getAccountId());
            return null;
        }
    }

    public function cancel(LoyaltyPointsCancelDTO $loyaltyPointsCancelDTO): ?LoyaltyPointsTransaction
    {
        $reason = $loyaltyPointsCancelDTO->getCancellationReason();

        if (empty($reason) || empty($loyaltyPointsCancelDTO->getTransactionId())) {
            return null;
        }

        if ($transaction = LoyaltyPointsTransaction::where('id', '=', $loyaltyPointsCancelDTO->getTransactionId())->where('canceled', '=', 0)->first()) {
            $transaction->canceled = time();
            $transaction->cancellation_reason = $reason;
            try {
                $transaction->save();
            } catch (Exception $exception) {
                $this->loggerService->logError('Cannot save cancellation transaction: ' . $exception->getMessage());
                throw new Exception($exception->getMessage(), 0, $exception);
            }

            return $transaction;
        } else {
            return null;
        }
    }

    public function withdraw(LoyaltyPointsWithdrawDTO $loyaltyPointsWithdrawDTO): ?LoyaltyPointsTransaction
    {
        $this->loggerService->logInfo('Withdraw loyalty points transaction for account id: ' . $loyaltyPointsWithdrawDTO->getAccountId());

        $type = $loyaltyPointsWithdrawDTO->getAccountType();
        $id = $loyaltyPointsWithdrawDTO->getAccountId();
        if (($type === 'phone' || $type === 'card' || $type === 'email') && $id !== '') {
            if ($account = LoyaltyAccount::where($type, '=', $id)->first()) {
                if ($account->active) {
                    if ($loyaltyPointsWithdrawDTO->getPointsAmount() <= 0) {
                        $this->loggerService->logInfo('Wrong loyalty points amount: ' . $loyaltyPointsWithdrawDTO->getPointsAmount());
                        return null;
                    }
                    if ($account->getBalance() < $loyaltyPointsWithdrawDTO->getPointsAmount()) {
                        $this->loggerService->logInfo('Insufficient funds: ' . $loyaltyPointsWithdrawDTO->getPointsAmount());
                        return null;
                    }

                    $transaction = LoyaltyPointsTransaction::withdrawLoyaltyPoints($account->id, $loyaltyPointsWithdrawDTO->getPointsAmount(),
                        $loyaltyPointsWithdrawDTO->getDescription());

                    $this->loggerService->logInfo('Withdraw amount by transaction: ' . $transaction->id);
                    return $transaction;
                } else {
                    $this->loggerService->logInfo('Account is not active: ' . $type . ' ' . $id);
                    return null;
                }
            } else {
                $this->loggerService->logInfo('Account is not found:' . $type . ' ' . $id);
                return null;
            }
        } else {
            $this->loggerService->logInfo('Wrong account parameters for accountId: ' . $loyaltyPointsWithdrawDTO->getAccountId());
            return null;
        }
    }
}
