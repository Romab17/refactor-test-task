<?php

namespace App\Utils;

use Illuminate\Support\Facades\Log;

class LoggerService
{
    public function logInfo(string $message): void {
        Log::info($message);
    }

    public function arrayToString(array $data): string {
        return serialize($data);
    }

    public function logError(string $message): void
    {
        Log::error($message);
    }
}
