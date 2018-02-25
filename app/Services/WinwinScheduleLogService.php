<?php
/**
 * Created by PhpStorm.
 * User: wugang
 * Date: 2017/4/5
 * Time: 下午2:42
 */
namespace App\Services;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class WinwinScheduleLogService
{

    private $log;

    public function __construct()
    {
        $this->log = new Logger('winwin');
        $logStreamHandler = new StreamHandler(storage_path('logs/run-' . date('Y-m-d') . '.log'), Logger::INFO);
        $format = "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n";
        $formatter = new LineFormatter($format);
        $logStreamHandler->setFormatter($formatter);
        $this->log->pushHandler($logStreamHandler);
    }

    public function info($message, array $context = array())
    {
        $this->log->addInfo($message, $context);
    }

    public function error($message, array $context = array())
    {
        $this->log->addError($message, $context);
    }
}