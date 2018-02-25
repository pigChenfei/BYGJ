<?php
/**
 * Created by PhpStorm.
 * User: winwin
 * Date: 2017/3/16
 * Time: 上午11:12
 */

namespace App\Vendor\GameGateway\Bbin;
use Curl\Curl;

class BBin
{

    const URL              = 'http://linkapi.bbinauth.net/app/WebService/JSON/display.php/';
    const URL1             = 'http://888.bbinauth.net/app/WebService/JSON/display.php/';

    const CreateMemberKeyB      = 'fA8eIS090';
    const LoginKeyB             = 'BaD6088';
    const Login2KeyB            = 'BaD6088';
    const LogoutKeyB            = '92E8E6a';
    const CheckUsrBalanceKeyB   = '0c4eI930d';
    const TransferKeyB          = 'Q38bF5aCq2';
    const CheckTransferKeyB     = 'cV4e56A83';
    const TransferRecordKeyB    = 'cV4e56A83';
    const PlayGameKeyB          = '4c8F93Hf9';
    const PlayGameByH5KeyB      = '4c8F93Hf9';
    const ForwardGameH5By30KeyB = '4c8F93Hf9';
    const ForwardGameH5By38KeyB = '4c8F93Hf9';
    const BetRecordKeyB         = '1E8PaDLk5c';
    const BetRecordByModifiedDate3KeyB = '1E8PaDLk5c';
    const GetJPHistoryKeyB      = '1E8PaDLk5c';

    const Website               = 'ttc1122';
    const Uppername             = 'dttc1122';

    const Gmt                   = -14400;

    //创建用户
    const API_CreateMember     ='http://linkapi.bbinauth.net/app/WebService/JSON/display.php/CreateMember';

    //会员登出
    const API_Logout           ='http://linkapi.bbinauth.net/app/WebService/JSON/display.php/Logout';

    //查询会员余额
    const API_CheckUsrBalance  ='http://linkapi.bbinauth.net/app/WebService/JSON/display.php/CheckUsrBalance';

    //转帐
    const API_Transfer         ='http://linkapi.bbinauth.net/app/WebService/JSON/display.php/Transfer';

    //查询会员转帐是否成功
    const API_CheckTransfer    ='http://linkapi.bbinauth.net/app/WebService/JSON/display.php/CheckTransfer';

    //查询会员转帐记录
    const API_TransferRecord   ='http://linkapi.bbinauth.net/app/WebService/JSON/display.php/TransferRecord';


    //下注纪录
    const API_BetRecord        ='http://linkapi.bbinauth.net/app/WebService/JSON/display.php/BetRecord';

    //下注纪录(查开奖时间)
    const API_BetRecordByModifiedDate3 ='http://linkapi.bbinauth.net/app/WebService/JSON/display.php/BetRecordByModifiedDate3';

    //JP开奖历史记录,不分体系
    const API_GetJPHistory     ='http://linkapi.bbinauth.net/app/WebService/JSON/display.php/GetJPHistory';

    //登录大厅
    const API_Login            ='http://888.bbinauth.net/app/WebService/JSON/display.php/Login';

    //会员登录
    const API_Login2           ='http://888.bbinauth.net/app/WebService/JSON/display.php/Login2';

    //直接导入flash
    const API_PlayGame         ='http://888.bbinauth.net/app/WebService/JSON/display.php/PlayGame';

    //直接导入真人H5游戏
    const API_PlayGameByH5     ='http://888.bbinauth.net/app/WebService/JSON/display.php/PlayGameByH5';

    //直接导入H5电子游戏
    const API_ForwardGameH5By5 ='http://888.bbinauth.net/app/WebService/JSON/display.php/ForwardGameH5By5';

    //捕鱼达人
    const API_ForwardGameH5By30 ='http://888.bbinauth.net/app/WebService/JSON/display.php/ForwardGameH5By30';

    //直接导入H5电子游戏
    const API_ForwardGameH5By38 ='http://888.bbinauth.net/app/WebService/JSON/display.php/ForwardGameH5By38';


    //$param为键值对数组,必须包含username,keyb,front,after,website
    public function remoteApi($url,$param,$isjumpout,$hasexitusername=1)
    {
        $datestr = gmdate('Ymd', time() + self::Gmt);
        $md5str='';
        if(isset($param['remitno']))
        {
            $md5str = strtolower(md5(utf8_encode(self::Website . $param['username'] .$param['remitno']. $param['keyb'] . $datestr)));
        }
        else
        {
            if(isset($param['username']))
            {
                $md5str = strtolower(md5(utf8_encode(self::Website . $param['username'] . $param['keyb'] . $datestr)));
            }
            else
            {
                $md5str = strtolower(md5(utf8_encode(self::Website . $param['keyb'] . $datestr)));
            }
            
        }
        $key = $this->createRand($param['front']) . $md5str . $this->createRand($param['after']);
        unset($param['front']);
        unset($param['after']);
        unset($param['keyb']);
        $param['key']=$key;
        if($isjumpout)
        {
            $str='';
            $tagurl='';
            foreach ($param as $k=> $v)
            {
                $str.= '&'.$k.'='.$v;
            }
            echo '<script>window.location = "'.$url.'?'.$str.'";</script>';
        }
        else
        {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
            $output = curl_exec($ch);
            curl_close($ch);
            if($output === false)  
            {
                return false;
            }
            else
            {
                return json_decode($output,true);
            }  
            
        }
    }

    private function createRand($number)
    {
        switch ($number) {
            case 1:
                $random=mt_rand(1, 9);
                break;
            case 2:
                $random=mt_rand(10, 99);
                break;
            case 3:
                $random=mt_rand(100, 999);
                break;
            case 4:
                $random=mt_rand(1000, 9999);
                break;
            case 5:
                $random=mt_rand(10000, 99999);
                break;
            case 6:
                $random=mt_rand(100000, 999999);
                break;
            case 7:
                $random=mt_rand(1000000, 9999999);
                break;
            case 8:
                $random=mt_rand(10000000, 99999999);
                break;
            case 9:
                $random=mt_rand(100000000, 999999999);
                break;
            default:
                break;
        }
        return $random;
    }
   
}