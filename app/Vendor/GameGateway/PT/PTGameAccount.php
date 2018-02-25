<?php
/**
 * Created by PhpStorm.
 * User: winwin
 * Date: 2017/3/16
 * Time: 下午1:49
 */

namespace App\Vendor\GameGateway\PT;


use App\Models\PlayerGameAccount;

class PTGameAccount
{

    public $playerGameAccount;

    public function __construct(PlayerGameAccount $account)
    {
        $this->playerGameAccount = $account;
    }

    /**
     * @return array|mixed|null
     */
    public function loginUserName(){
        return $this->getInfoData('loginUserName');
    }

    /**
     * @return array|mixed|null
     */
    public function loginPassword(){
        return $this->getInfoData('loginPassword');
    }

    /**
     * @param $userName
     */
    public function setLoginUserName($userName){
        $this->setInfoData('loginUserName',$userName);
    }

    /**
     * @param $password
     */
    public function setLoginPassword($password){
        $this->setInfoData('loginPassword',$password);
    }

    /**
     * @param $key
     * @param $value
     */
    private function setInfoData($key, $value){
        $data = $this->getInfoData();
        $data[$key] = $value;
        $this->playerGameAccount->extra_field = json_encode($data);
    }

    /**
     * @param null $key
     * @return array|mixed|null
     */
    private function getInfoData($key = null){
        $extraField = $this->playerGameAccount->extra_field;
        $extraData  = [];
        $extraField && $extraData = json_decode($extraField,true);
        if(!$key){
            return $extraData;
        }
        if(isset($extraData[$key])){
            return $extraData[$key];
        }
        return null;
    }

}