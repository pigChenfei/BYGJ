<?php
/**
 * Created by PhpStorm.
 * User: win7
 * Date: 2017/11/25
 * Time: 14:50
 */
namespace App\Vendor\Game;
use Curl\Curl;

class MG{
    
    const HOST              = 'https://api.adminserv88.com/';
    const USERNAME          = 'TTCCNYAPI';
    const PASSWORD          = 'ss#maEHF#hTR+=7S2yRtYD6&';
    const PARENTID          = '2844210';
    const AUTH              = 'R2FtaW5nTWFzdGVyMUNOWV9hdXRoOjlGSE9SUWRHVFp3cURYRkBeaVpeS1JNZ1U=';
    const API_TOKEN         = 'oauth/token';

    //创建用户
    const API_CREATEMEMBER          = 'v1/account/member';

    //转帐
    const API_TRANSACTION           = 'v1/transaction';

    //查询余额
    const API_WALLET                = 'v1/wallet';

    //进入游戏
    const API_LAUNCHER              = 'v1/launcher/item';

    //查询订单
    const API_TRANSACTIONROUND      = 'v1/feed/transactionround';



    private function mgCurl($url,$body='',$method='post')
    {
        $access_token = $this ->token() ;
        //设置 Header
        $header[] = 'Authorization: Bearer ' . $access_token;
        $header[] = 'X-DAS-TZ: UTC+8';
        $header[] = 'X-DAS-CURRENCY: CNY';
        $header[] = 'X-DAS-LANG: zh-CN';
        $header[] = 'X-DAS-TX-ID: TEXT-TX-ID';
        $header[] = 'Content-Type: application/json;charset=UTF-8';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if($method=='post')
        {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        }
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $output = curl_exec($ch);
        if(!$output)
        {
            return false;
        }
        else
        {
            return json_decode($output,true);
        }
    }

    /**
     * 查询下单流水
     */
    public function betRecord($longminute)
    {
        $time               = time();
        $longminutetime     = $longminute*60;
        $dawn               = strtotime(date('Y-m-d',$time));
        $end_time           = str_replace(' ','T',date("Y-m-d H:i:s",$time));
        $start_time         = '';

        if($time-$longminutetime>$dawn)
        {
            $start_time = $time-$longminutetime;
            $start_time = str_replace(' ','T',date("Y-m-d H:i:s",$start_time));
        }
        else
        {
            $start_time = str_replace(' ','T',date("Y-m-d H:i:s",$dawn));
        }

        $url       = self::HOST.self::API_TRANSACTIONROUND.'?company_id='.self::PARENTID.'&start_time='.$start_time.'&end_time='.$end_time.'&include_transfers=false&include_end_round=true';
        $output    = $this->ptMorePage($url);
        return $output;
    }


    /**
     *@param 参数
     *@抓取多页数据
     */
    private function ptMorePage($url)
    {
        $totalresult    = array();
        $bool           = true;
        $page           = 1;
        do
        {
            $purl=$url.'&page='.$page;
            $result = $this->mgCurl($purl,'','get');
            $res    = array();

            if($result&&$result['data']&&count($result['data']))
            {
                foreach($result['data'] as $r)
                {
                    $res['gameAccount']             = $r['account_ext_ref'];
                    $res['gameId']                  = $r['meta_data']['item_id'];
                    $res['orderId']                 = $r['meta_data']['round_seq_id'];
                    $res['betAmount']               = $r['sum_of_wager'];
                    $res['orderDate']               = $r['start_time'];
                    $res['payOff']                  = $r['sum_of_payout']>0?$r['sum_of_payout']-$r['sum_of_wager']:0;
                    $res['isPaid']                  = $r['status']=="CLOSED"?1:0;

                    //sum_of_refund  退费，可能会依网络问题造成投注失败
                    if ($r['sum_of_refund'] > 0) 
                    {          
                        $res['isEffective']      = 0;            //无效投注
                        $res['commissionable']   = 0;
                    } 
                    else 
                    {
                        $res['isEffective']     =  1; //有效投注
                        $res['commissionable']  = $r['sum_of_wager'] ; //有效投注额
                    }

                    array_push($totalresult,$res);
                }
                $page++;
            }
            if(!$result||!count($result['data']))
            {
                $bool   = false;
            }
           
        }while($bool);
        return $totalresult;
    }

    /**
     * 检查订单
     */
    public function checkTransfer($accountUserName,$orderId)
    {
         $url       = self::HOST.self::API_TRANSACTION.'?account_ext_ref='.$accountUserName.'&ext_ref='.$orderId;
         $output    = $this ->mgCurl($url,'','get') ;
         if($output&&count($output['data']))
         {
            return true;
         }
         else if(!$output)
         {
            return 'unknown';
         }
         else
         {
             return false;
         }
    }

    /**
     *进入游戏
     */
    public function playgameH5($accountUserName,$app_id,$item_id)
    {
        $url    =self::HOST.self::API_LAUNCHER;
        $param = array
        (
            'ext_ref' => $accountUserName ,
            'item_id' => $item_id ,
            'app_id' => $app_id
        );
        $output =$this->mgCurl($url,json_encode($param));
        if(!$output)
        {
            return $output;
        }
        else
        {
            Header("Location:".$output['data']);
        }
    }

    /**
     *查询余额
     */
    public function checkBalance($accountUserName)
    {
        $url    =self::HOST.self::API_WALLET.'?account_ext_ref=' . $accountUserName;
        $output =$this->mgCurl($url,'','get');
        if($output&&isset($output['data']))
        {
            return $output['data'][0]['credit_balance'];
        }
        else
        {
            return false;
        }
    }

    /**
     *生成随机字符串
     */
    private function generate_value( $length = 8 )
    {
        // 密码字符集，可任意添加你需要的字符
        $chars ='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXY0123456789';
        $value ='';
        for ( $i = 0;$i < $length; $i++ )
        {
            $value .= $chars[ mt_rand(0, strlen($chars) - 1) ];
        }
        return $value;
    }

    /**
     * 转入帐号
     */
    public function transferIn($accountUserName,$money)
    {
        $orderId        = date('YmdHis',time()) ; //当前时间
        $url            =self::HOST.self::API_TRANSACTION;
        $param = array
        (
            'account_ext_ref'   => $accountUserName ,
            'external_ref'      => $orderId ,
            'amount'            => $money,
            'type'              => 'CREDIT' ,
            'balance_type'      => 'CREDIT_BALANCE',
            'category'          => 'TRANSFER'
        );
        $output = $this ->mgCurl($url,json_encode(array($param)));
        if(!$output)
        {
            return array('result'=>'unknown','accountUserName'=>$accountUserName,'money'=>$money,'ordernumber'=>$orderId);
        }
        else if($output&&isset($output['data']))
        {
            return array('result'=>true,'accountUserName'=>$accountUserName,'money'=>$money,'ordernumber'=>$orderId);
        }
        else
        {
            return array('result'=>false,'accountUserName'=>$accountUserName,'money'=>$money,'ordernumber'=>$orderId);
        }
        return $output;
    }

    /**
     * 转出帐号
     */
    public function transferOut($accountUserName,$money)
    {
        $orderId        = date('YmdHis',time()) ; //当前时间
        $url            =self::HOST.self::API_TRANSACTION;
        $param = array
        (
            'account_ext_ref'   => $accountUserName ,
            'external_ref'      => $orderId ,
            'amount'            => $money,
            'type'              => 'DEBIT' ,
            'balance_type'      => 'CREDIT_BALANCE',
            'category'          => 'TRANSFER'
        );

        $output = $this ->mgCurl($url,json_encode(array($param)));
        if(!$output)
        {
            return array('result'=>'unknown','accountUserName'=>$accountUserName,'money'=>$money,'ordernumber'=>$orderId);
        }
        else if($output&&isset($output['data']))
        {
            return array('result'=>true,'accountUserName'=>$accountUserName,'money'=>$money,'ordernumber'=>$orderId);
        }
        else
        {
            return array('result'=>false,'accountUserName'=>$accountUserName,'money'=>$money,'ordernumber'=>$orderId);
        }
        return $output;
    }
    
    /**
     *创建用户
     */
    public function createMember($pre)
    {
        $url=self::HOST.self::API_CREATEMEMBER;
        
        $playeraccount=$pre.'Z'.$this->generate_value(6);

        //设置 body
        $param  = array('parent_id' => self::PARENTID,'username' => $playeraccount,'password' => $this->generate_value(8));
        $output = $this ->mgCurl($url,json_encode($param));

        if($output&&isset($output['data']))
        {
            return array('playeraccount'=>$playeraccount,'password'=>$param['password']);
        }
        else
        {
            return false;
        }
    }

    //获取TOKEN
    private function token()
    {
        $header  = array(
            'Authorization: Basic ' . self::AUTH,
            'application/x-www-form-urlencoded;charset=utf-8',
            'X-DAS-TZ: UTC+8',
            'X-DAS-CURRENCY: CNY',
            'X-DAS-LANG: zh-CN',
            'X-DAS-TX-ID: TEXT-TX-ID'
        );

        $param = array
        (
            'grant_type'    => 'password',
            'username'      => self::USERNAME,
            'password'      => self::PASSWORD
        );
        $url=self::HOST.self::API_TOKEN;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $output = curl_exec($ch);
        $out    = json_decode($output,true);
        if(isset($out['access_token']))
        {
            return $out['access_token'];
        }
        else
        {
            return false;
        }
    }
}