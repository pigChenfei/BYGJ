<?php

namespace  App\Vendor\GameGateway\Query;
/**
 * Created by PhpStorm.
 * User: winwin
 * Date: 2017/3/16
 * Time: 下午1:20
 */
class QueryResult
{

    public $error = null;

    public $errorCode = null;

    public $errorMessage = null;

    public $data = null;

    public function getErrorMessage(){
        return $this->errorMessage;
    }

    public function getResponseData(){
        return $this->data;
    }
}