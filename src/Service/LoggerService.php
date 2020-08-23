<?php
declare(strict_types=1);

namespace App\Service;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

/**
 * Class LoggerService
 * @package App\Service
 */
class LoggerService
{
    /**
     * @param array $message
     */
    public function logDailyReport(array $message): void
    {
        $logger = new Logger('my_logger');
        $logger->pushHandler(new StreamHandler(__DIR__.'/../logs/daily_report' . time() . '.log', Logger::DEBUG));
        $logger->info(json_encode($message));
    }
}