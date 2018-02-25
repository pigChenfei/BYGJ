<?php

namespace App\Repositories\Admin;

use App\Models\Def\BankType;
use InfyOm\Generator\Common\BaseRepository;

class BankTypeRepository extends BaseRepository
{

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'bank_name',
        'bank_type'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return BankType::class;
    }


    /**
     * 获取所有的银行卡类型
     * @return mixed
     */
    public function allBankTypes(){
        return $this->all();
    }
}
