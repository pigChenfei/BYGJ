<?php
namespace App\Models\System;
/**
 * Created by PhpStorm.
 * User: winwin
 * Date: 2017/3/28
 * Time: 下午8:52
 */
class ReminderEmail
{

    public $errorMessage;

    public $errorDebugTrace;

    public function __construct(\Exception $e)
    {
        $this->errorMessage = $e->getMessage();
        $this->errorDebugTrace = $e->getTraceAsString();
    }

}