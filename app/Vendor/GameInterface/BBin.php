<?php
/**
 * Created by PhpStorm.
 * User: winwin
 * Date: 2017/3/16
 * Time: 上午11:12
 */

namespace App\Vendor\GameInterface;

use Curl\Curl;

class BBin
{
    //服务器时区GMT -4
    const URL                   = 'http://linkapi.bbinauth.net/app/WebService/JSON/display.php/';
    const URL1                  = 'http://888.bbinauth.net/app/WebService/JSON/display.php/';
    const URL2                  = 'http://777.bbingames.net/';  //试玩URL
    const Website               = 'ttc1122';
    const Uppername             = 'dttc1122';
    const Gmt                   = -14400;


    const CreateMemberKeyB              = 'fA8eIS090';
    const LoginKeyB                     = 'BaD6088';
    const Login2KeyB                    = 'BaD6088';
    const LogoutKeyB                    = '92E8E6a';
    const CheckUsrBalanceKeyB           = '0c4eI930d';
    const TransferKeyB                  = 'Q38bF5aCq2';
    const CheckTransferKeyB             = 'cV4e56A83';
    const TransferRecordKeyB            = 'cV4e56A83';
    const PlayGameKeyB                  = '4c8F93Hf9';
    const PlayGameByH5KeyB              = '4c8F93Hf9';
    const ForwardGameH5By30KeyB         = '4c8F93Hf9';
    const ForwardGameH5By38KeyB         = '4c8F93Hf9';
    const BetRecordKeyB                 = '1E8PaDLk5c';
    const BetRecordByModifiedDate3KeyB  = '1E8PaDLk5c';
    const GetJPHistoryKeyB              = '1E8PaDLk5c';

    //创建用户
    const API_CreateMember              = self::URL.'CreateMember';

    //会员登出
    const API_Logout                    =self::URL.'Logout';

    //查询会员余额
    const API_CheckUsrBalance           =self::URL.'CheckUsrBalance';

    //转帐
    const API_Transfer                  =self::URL.'Transfer';

    //查询会员转帐是否成功
    const API_CheckTransfer             =self::URL.'CheckTransfer';

    //查询会员转帐记录
    const API_TransferRecord            =self::URL.'TransferRecord';

    //下注纪录
    const API_BetRecord                 =self::URL.'BetRecord';

    //下注纪录(查开奖时间)
    const API_BetRecordByModifiedDate3  =self::URL.'BetRecordByModifiedDate3';

    //JP开奖历史记录,不分体系
    const API_GetJPHistory              =self::URL.'GetJPHistory';

    //登录大厅
    const API_Login                     =self::URL1.'Login';

    //会员登录
    const API_Login2                    =self::URL1.'Login2';

    //直接导入flash
    const API_PlayGame                  =self::URL1.'PlayGame';

    //直接导入真人H5游戏
    const API_PlayGameByH5              =self::URL1.'PlayGameByH5';

    //直接导入H5电子游戏
    const API_ForwardGameH5By5          =self::URL1.'ForwardGameH5By5';

    //捕鱼达人
    const API_ForwardGameH5By30         =self::URL1.'ForwardGameH5By30';

    //直接导入H5电子游戏
    const API_ForwardGameH5By38         =self::URL1.'ForwardGameH5By38';


     /**
     * bbincurl抓取
     */

    private function bbinCurl($url,$param)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        $output = curl_exec($ch);
        $error  = curl_error($ch);
        curl_close($ch);
        if (!empty($error)) 
        {
            return false;
        }
        else
        {
            return json_decode($output,true);
        }
    }

    //创建用户
    public function createMember($accountUserName)
    {
        $param=array(
            'username'=>$accountUserName,
            'uppername'=>BBin::Uppername,
            'website'=>BBin::Website
            );

        $datestr        = gmdate('Ymd', time() + self::Gmt);
        $md5str         = strtolower(md5(utf8_encode(self::Website . $accountUserName . self::CreateMemberKeyB . $datestr)));
        $param['key']   = $this->createRand(5) . $md5str . $this->createRand(2);
        $result         = $this->bbinCurl(BBin::API_CreateMember,$param);

        if($result||$result['result'])
        {
            return array('username'=>$username,'flag'=>'success');
        }
        else
        {
            return array('flag'=>'false');
        }
    }

    /**
     *@accountUserName 平台游戏帐号
     *@踢用户下线
     */
    public function kick($accountUserName)
    {
        $param=array(
            'username'=>$accountUserName,
            'website'=>self::Website
            );
        $datestr        = gmdate('Ymd', time() + self::Gmt);
        $md5str         = strtolower(md5(utf8_encode(self::Website . $accountUserName . self::LogoutKeyB . $datestr)));
        $param['key']   = $this->createRand(4) . $md5str . $this->createRand(6);

        $result         = $this->bbinCurl(BBin::API_Logout,$param);
        if($result&&$result['result'])
        {
            return array('flag'=>'true');
        }
        else
        {
            return array('flag'=>'false');
        }
    }

    //查询余额
    public function checkBalance($accountUserName)
    {
        $param=array(
            'username'=>$accountUserName,
            'uppername'=>self::Uppername,
            'website'=>self::Website
        );
        $datestr        = gmdate('Ymd', time() + self::Gmt);
        $md5str         = strtolower(md5(utf8_encode(self::Website . $param['username'] . self::CheckUsrBalanceKeyB . $datestr)));
        $param['key']   = $this->createRand(9) . $md5str . $this->createRand(6);

        $result =$this->bbinCurl(self::API_CheckUsrBalance,$param);

        if($result&&$result['result'])
        {
            return array('flag'=>'true','balance'=>$result['data'][0]['TotalBalance']);
        }
        else
        {
            return array('flag'=>'false');
        }
    }

    //转入平台
    public function transferIn($accountUserName,$money)
    {
        $remitno=time().mt_rand(10000000,99999999);
        $param=array(
            'username'=>$accountUserName,
            'remit'=>$money,
            'remitno'=>$remitno,
            'action'=>'IN',
            'uppername'=>self::Uppername,
            'website'=>self::Website
        );
        $datestr        = gmdate('Ymd', time() + self::Gmt);
        $md5str         = strtolower(md5(utf8_encode(self::Website . $param['username'] .$remitno. self::TransferKeyB . $datestr)));
        $param['key']   = $this->createRand(2) . $md5str . $this->createRand(7);
        $result         = $this->bbinCurl(BBin::API_Transfer,$param);

        if(isset($result['result'])&&$result['result']=='True')
        {
            return array('flag'=>'true','orderNumber'=>$remitno);
        }
        else if(!$result)
        {
            return array('flag'=>'unknown','orderNumber'=>$remitno);
        }
        else
        {
            return array('flag'=>'false','orderNumber'=>$remitno);
        }
    }

    //转出单号
    public function transferOut($accountUserName,$money)
    {
        $remitno=time().mt_rand(10000000,99999999);
        $param=array(
            'username'  =>$accountUserName,
            'remit'     =>$money,
            'remitno'   =>$remitno,
            'action'    =>'OUT',
            'uppername' =>self::Uppername,
            'website'   =>self::Website
        );
        $datestr        = gmdate('Ymd', time() + self::Gmt);
        $md5str         = strtolower(md5(utf8_encode(self::Website . $param['username'] .$remitno. self::TransferKeyB . $datestr)));
        $param['key']   = $this->createRand(2) . $md5str . $this->createRand(7);
        $result         = $this->bbinCurl(BBin::API_Transfer,$param);

        if(isset($result['result'])&&$result['result']=='True')
        {
            return array('flag'=>'true','orderNumber'=>$remitno);
        }
        else if(!$result)
        {
            return array('flag'=>'unknown','orderNumber'=>$remitno);
        }
        else
        {
            return array('flag'=>'false','orderNumber'=>$remitno);
        }
    }

    //转帐查询
    public function checkTransfer($orderNumber)
    {
        $param=array(
            'transid'=>$orderNumber,
            'website'=>self::Website
            );
        $datestr        = gmdate('Ymd', time() + self::Gmt);
        $md5str         = strtolower(md5(utf8_encode(self::Website .  self::CheckTransferKeyB . $datestr)));
        $param['key']   = $this->createRand(5) . $md5str . $this->createRand(4);
        $result         = $this->bbinCurl(BBin::API_CheckTransfer,$param);

        if(isset($result['result'])||$result['result'])
        {
            if($result['Status']==1)
            {
                return array('flag'=>'true');
            }
            else
            {
                return array('flag'=>'false');
            }
        }
        else
        {
            return array('flag'=>'unknown');
        }
    }

    //用户登录
    private function login($accountUserName)
    {
        $param=array(
            'username'=>$accountUserName,
            'uppername'=>self::Uppername,
            'website'=>self::Website
        );
        $datestr        = gmdate('Ymd', time() + self::Gmt);
        $md5str         = strtolower(md5(utf8_encode(self::Website . $param['username'] . self::Login2KeyB . $datestr)));
        $param['key']   = $this->createRand(8) . $md5str . $this->createRand(1);

        $result = $this->bbinCurl(self::API_Login2,$param);
    }

    /**
     *@array('accountUserName'=>'','gametype'=>'','gamekind'=>'','ismobile'=>'')
     *当设置ismobile时是手机端
     */
    public function playgamePc($array)
    {
        $this->login($array['accountUserName']);
        $param=array(
                'username'=>$array['accountUserName'],
                'gametype'=>$array['gametype'],
                'website'=>self::Website
            );
        $datestr        = gmdate('Ymd', time() + self::Gmt);
        $str='';
        $url='';
        if($array['gametype']==30599)
        {
            //捕鱼达人
            $param['uppername'] =self::Uppername;
            $md5str         = strtolower(md5(utf8_encode(self::Website . $param['username'] . self::ForwardGameH5By30KeyB . $datestr)));
            $param['key']   = $this->createRand(7) . $md5str . $this->createRand(1);
            $url            = self::API_ForwardGameH5By30;
        }
        else if($array['gametype']==38001)
        {
            //捕鱼大师
            $param['uppername'] =self::Uppername;
            $datestr        = gmdate('Ymd', time() + self::Gmt);
            $md5str         = strtolower(md5(utf8_encode(self::Website . $param['username'] . self::ForwardGameH5By38KeyB . $datestr)));
            $param['key']   = $this->createRand(7) . $md5str . $this->createRand(1);
            $url            = self::API_ForwardGameH5By38;
        }
        else
        {
            //电子游H5登录
            
            if(isset($array['isMobile']))
            {
                $param['uppername']  =self::Uppername;
                $url=self::API_ForwardGameH5By5;
            }
            else
            {
                $param['gamekind']  =$array['gamekind'];
                $url=self::API_PlayGameByH5;
            }
            $md5str         = strtolower(md5(utf8_encode(self::Website . $param['username'] . self::PlayGameByH5KeyB . $datestr)));
            $param['key']   = $this->createRand(7) . $md5str . $this->createRand(1);
        }
        foreach ($param as $k=> $v)
        {
            $str.= '&'.$k.'='.$v;
        }
        echo '<script>window.location = "'.$url.'?'.$str.'";</script>';
    }

    //手机访问
    public function playgameMobile($array)
    {
        $this->playgamePc($array);
    }

    //登录大厅
    public function loginhall($array)
    {
        $param=array(
            'username'=>$array['accountUserName'],
            'uppername'=>self::Uppername,
            'website'=>self::Website
        );
        if(isset($array['type'])) $param['page_site'] = $array['type'];
        $datestr        = gmdate('Ymd', time() + self::Gmt);
        $md5str         = strtolower(md5(utf8_encode(self::Website . $array['accountUserName'] . self::LoginKeyB . $datestr)));
        $param['key']   = $this->createRand(8) . $md5str . $this->createRand(1);

        $str='';
        foreach ($param as $k=> $v)
        {
            $str.= '&'.$k.'='.$v;
        }
        echo '<script>window.location = "'.self::API_Login.'?'.$str.'";</script>';
    }

    //登录移动版大厅
    public function loginhallMobile($array)
    {
        $this->loginhall($array);
    }

    //试玩
    public function trial($array)
    {
        echo '<script>window.location = "'.self::URL2.'";</script>';
    }
    /**
     *@type=12 BB彩票,type=1 BB体育,type=5 BB电子,type=3 BB真人
     *@minute  多少分钟抓一次
     *@longminute 抓取多少分钟的数据
     *@isdate  是否只精确到天
     */
     public function betRecord($type,$longminute,$isdate=false)
     {
        $time=time()-43200;
        $totalresult    = array();
        $param          = array();
        $gametype='';
        $subgamekind='';
        if($type==12)
        {
             $param=array(
                'uppername'=>self::Uppername,
                'website'=>self::Website,
                'page'=>'1',
                'gamekind'=>$type
            );
            $gametype=array('LT','OTHER');
        }
        else if($type==1)
        {
             $param=array(
            'uppername'=>BBin::Uppername,
            'website'=>BBin::Website,
            'page'=>'1',
            'gamekind'=>$type
            );
        }
        else if($type==5)
        {
            $param=array(
                'uppername'=>BBin::Uppername,
                'website'=>BBin::Website,
                'page'=>'1',
                'gamekind'=>$type
            );
            $subgamekind=array(1,2,3,5);
        }
        else if($type==3)
        {
            $param=array(
            'uppername'=>BBin::Uppername,
            'website'=>BBin::Website,
            'page'=>'1',
            'gamekind'=>$type
            );
        }
        if(!empty($gametype))
        {
            foreach($gametype as $v1)
            {
                $param['gametype']  = $v1;
                $param              = $this->timeInterval($param,$time,$longminute,$isdate);
                $result             = $this->bbinMorePage($param,$type);
                if(count($result))
                {
                    array_push($totalresult,$result);
                }
                
            } 
        }
        else if(!empty($subgamekind))
        {
             foreach($subgamekind as $v1)
            {
                $param['subgamekind']   = $v1;
                $param                  = $this->timeInterval($param,$time,$longminute,$isdate);
                $result                 = $this->bbinMorePage($param,$type);
                if(count($result))
                {
                    array_push($totalresult,$result);
                }
            }   
        }
        else
        {
            $param              = $this->timeInterval($param,$time,$longminute,$isdate);
            $totalresult        = $this->bbinMorePage($param,$type);
        }
        
        return $totalresult;          
     }

    /**
     *@param 参数
     *@抓取多页数据
     */
    private function bbinMorePage($param,$type)
    {
        $datestr        = gmdate('Ymd', time() + self::Gmt);
        $totalresult    = array();
        $bool           = true;
        do
        {
            $md5str         = strtolower(md5(utf8_encode(self::Website . self::BetRecordKeyB . $datestr)));
            $param['key']   = $this->createRand(1) . $md5str . $this->createRand(7);
            $result = $this->bbinCurl(BBin::API_BetRecord,$param);
            $res    = array();

            //BB彩票
            if($type==12)
            {
                if($result&&$result['result']&&count($result['data']))
                {
                    foreach($result['data'] as $r)
                    {
                        $res['gameAccount']             = $r['UserName'];
                        $res['orderId']                 = $r['WagersID'];
                        $res['gameType']                = $r['GameType'];
                        $res['betAmount']               = $r['BetAmount'];
                        $res['orderDate']               = $r['WagersDate'];
                        $res['isEffective']             = $r['Result']=='N2'?0:1;
                        $res['payOff']                  = $r['Payoff']<0?0:$r['Payoff'];
                        $res['isPaid']                  = $r['IsPaid']=='Y'?1:0;
                        $res['commissionable']          = $r['Result']=='N2'?0:$res['betAmount'];
                        array_push($totalresult,$res);
                    }
                    $param['page']=$param['page']+1;
                }
                if(!$result||!$result['result']||$result['pagination']['TotalPage']==1||$result['pagination']['TotalPage']==0||($result['pagination']['Page']==$result['pagination']['TotalPage']))
                {
                    $bool   = false;
                }
            }
            //BB体育
            else if($type==1)
            {
                if($result&&$result['result']&&count($result['data']))
                {
                    foreach($result['data'] as $r)
                    {
                        $res['gameAccount']   = $r['UserName'];
                        $res['orderId']       = $r['WagersID'];
                        $res['gameType']      = $r['GameType'];
                        $res['orderDate']     = $r['WagersDate'];
                        $res['betAmount']     = $r['BetAmount'];
                        $res['payOff']        = $r['Payoff']<0?0:$r['Payoff'];
                        $res['commissionable']= $r['Commissionable'];
                        $res['isEffective']   = ($r['Result']=='C'||$r['Result']=='F')?0:1;

                        if($r['Result']=='X'||$r['Result']=='S'||$r['Result']=='D'||$r['Result']=='N')
                        {
                            $res['isPaid']        = 0;
                        }
                        else
                        {
                            $res['isPaid']        = 1;
                        }
                        
                        array_push($totalresult,$res);
                    }
                    $param['page']=$param['page']+1;
                }
                if(!$result||!$result['result']||$result['pagination']['TotalPage']==1||$result['pagination']['TotalPage']==0||($result['pagination']['Page']==$result['pagination']['TotalPage']))
                {
                    $bool   = false;
                }
            }
            //BB电子
            else if($type==5)
            {
                if($result&&$result['result']&&count($result['data']))
                {
                    foreach($result['data'] as $r)
                    {
                        $res['gameAccount']   = $r['UserName'];
                        $res['orderId']       = $r['WagersID'];
                        $res['gameType']      = $r['GameType'];
                        $res['orderDate']     = $r['WagersDate'];
                        $res['betAmount']     = $r['BetAmount'];
                        $res['payOff']        = $r['Payoff']<0?0:$r['Payoff'];
                        $res['commissionable']= $r['Commissionable'];
                        $res['isEffective']   = $r['Result']=='-1'?0:1;

                        if($r['Result']=='1'||$r['Result']=='200')
                        {
                            $res['isPaid']        = 1;
                        }
                        else
                        {
                            $res['isPaid']        = 0;
                        }
                        
                        array_push($totalresult,$res);
                    }
                    $param['page']=$param['page']+1;
                }

                if(!$result||!$result['result']||$result['pagination']['TotalPage']==1||$result['pagination']['TotalPage']==0||($result['pagination']['Page']==$result['pagination']['TotalPage']))
                {
                    $bool   = false;
                }
            
            }
            //BB真人
            else if($type==3)
            {
                if($result&&$result['result']&&count($result['data']))
                {
                    foreach($result['data'] as $r)
                    {
                        $res['gameAccount']   = $r['UserName'];
                        $res['orderId']       = $r['WagersID'];
                        $res['gameType']      = $r['GameType'];
                        $res['orderDate']     = $r['WagersDate'];
                        $res['betAmount']     = $r['BetAmount'];
                        $res['payOff']        = $r['Payoff']<0?0:$r['Payoff'];
                        $res['commissionable']= $r['Commissionable'];
                        $res['isEffective']   = $r['ResultType']=='-1'?0:1;

                        if($r['ResultType']=='0')
                        {
                            $res['isPaid']        = 0;
                        }
                        else
                        {
                            $res['isPaid']        = 1;
                        }
                        
                        array_push($totalresult,$res);
                    }
                    $param['page']=$param['page']+1;
                }

                if(!$result||!$result['result']||$result['pagination']['TotalPage']==1||$result['pagination']['TotalPage']==0||($result['pagination']['Page']==$result['pagination']['TotalPage']))
                {
                    $bool   = false;
                }
            }
        }while($bool);
        return $totalresult;
    }

    /*
    *@isdate     是否只需精确到天
    *@longminute 抓取多长时间的数据
    *@time       当前时间
    */
    private function timeInterval($param,$time,$longminute,$isdate)
    {
        $longminutetime =   $longminute*60;

        //当天凌晨的时间戳
        $dawn = strtotime(date('Y-m-d',$time));

        if($isdate)
        {
            //查询当天时间
            $param['rounddate']=date("Y/m/d",$time);
        }
        else
        {
            if($time-$longminutetime>=$dawn)
            {
                //查询时长不跨天
                $param['rounddate']=date("Y/m/d",$time);
                $param['starttime']=date("H:i:s",$time-$longminutetime);
                $param['endtime']  =date("H:i:s",$time);
            }
            else
            {
                //查询时长跨天
                $param['rounddate']=date("Y/m/d",$time);
                $param['starttime']=date("H:i:s",$dawn);
                $param['endtime']  =date("H:i:s",$time);
            }
        }
        return $param;
    }



    /**
     * 产生随机数
     */

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