<?php

namespace App\Utils;

use App\Models\LoyaltyAccount;
use Exception;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;

class MailService
{
    private LoggerService $loggerService;
    public function __construct(LoggerService $loggerService)
    {
        $this->loggerService = $loggerService;
    }

    /**
     * @throws Exception
     */
    public function sendMail(LoyaltyAccount $account, Mailable $message): void
    {
        try {
            Mail::to($account)->send($message);
        } catch (Exception $exception) {
            $this->loggerService->logError('Error sending mail: ' . $exception->getMessage());
            throw new Exception($exception->getMessage(), 0, $exception);
        }
    }
}
