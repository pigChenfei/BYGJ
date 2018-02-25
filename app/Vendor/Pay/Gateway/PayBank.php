<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/5
 * Time: 14:55
 */

namespace App\Vendor\Pay\Gateway;


class PayBank
{
    public $code; //银行代码
    public $bankName;//银行名称
    public $icon;//银行图片

    public function __construct($code,$bankName,$icon)
    {
        $this->code = $code;
        $this->bankName = $bankName;
        $this->icon = $icon;

    }
}