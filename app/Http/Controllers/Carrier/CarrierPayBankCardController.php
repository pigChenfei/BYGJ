<?php

namespace App\Http\Controllers\Carrier;
use App\Http\Controllers\AppBaseController;


class CarrierPayBankCardController extends AppBaseController
{

    public function __construct()
    {
        
    }

    /**
     * 银行卡账户列表
     */
    public function index(\App\DataTables\Carrier\CarrierPayBankCardDataTable $arrierPayBankCardDataTable)
    {
        return $arrierPayBankCardDataTable->render('Carrier.carrier_pay_bank_cards.index');
    }
    
    /**
     * 
     * @return type
     */
    public function account_list()
    {
        return view('Carrier.carrier_pay_bank_cards.account_list');
    }
    
    /**
     * 添加银行卡账户
     * @return type
     */
    public function create()
    {
        return view('Carrier.carrier_pay_bank_cards.create');
    }

    public function show()
    {
        return view('Carrier.carrier_pay_bank_cards.account_list');
    }
    
}
