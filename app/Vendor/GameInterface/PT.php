<?php
/**
 * Created by PhpStorm.
 * User: winwin
 * Date: 2017/3/16
 * Time: 上午11:12
 */

namespace App\Vendor\GameInterface;
use Curl\Curl;

class PT
{
    const API_HOST      = 'https://kioskpublicapi.luckyspin88.com/'; 
    const ENTITYKEY     = 'd97ddafeeb4f82adc72ae4e558cc292bd23ad85c229600c5c6481876b472a964a47dd93879df24b350c38bc3762d86f1f1a6244e1dc11c2b24003e80decbb3f4';
    const KIOSKNAME     =  'TTC';
    const ADMINNAME     =  'TTCADMIN';

    const Gmt                   = 28800;

    //创建用户
    const API_PLAYER_CREATE = 'player/create';

    //查询余额
    const API_PLAYER_BALANCE = 'player/balance';

    //转出游戏
    const API_PLAYER_WITHDRAW   = 'player/withdraw';

    //转入游戏
    const API_PLAYER_DEPOSIT    = 'player/deposit';

    //游戏踢线
    const API_PLAYER_LOGOUT     = 'player/logout';

    //用户信息更新
    const API_PLAYER_UPDATE     = 'player/update';

    //抓取下注单数据
    const API_PLAYER_GAMES_BETTING_FLOW = 'customreport/getdata/reportname/PlayerGames';

    public function ptCurl($url,$param)
    {
        $header = [
        "Accept:text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
        "Cache-Control: max-age=0",
        "Connection: keep-alive",
        "Keep-Alive:timeout=5, max=100",
        "Accept-Charset:ISO-8859-1,utf-8;q=0.7,*;q=0.3",
        "Accept-Language:es-ES,es;q=0.8",
        "Pragma: ",
        "X_ENTITY_KEY:".self::ENTITYKEY
        ];

        foreach($param as $key =>$value)
        {
            $url.='/'.$key.'/'.$value;
        }
        $ch= curl_init();
        curl_setopt($ch, CURLOPT_PORT , 443);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSLCERT, app_path('Vendor/GameInterface/PT.pem'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSLKEY, app_path('Vendor/GameInterface/PT.key'));
        curl_setopt($ch, CURLOPT_URL,$url);
        $output = curl_exec($ch);
        $error  = curl_error($ch);
        curl_close($ch);
        if(!empty($error))  
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
        $url=self::API_HOST.self::API_PLAYER_CREATE;
        $param=array(
            'playername'=>$accountUserName,
            'adminname'=>self::ADMINNAME,
            'kioskname'=>self::KIOSKNAME
        );
        $result =$this->ptCurl($url,$param);
        if($result&&isset($result['result']))
        {
            return array('username'=>$accountUserName,'password'=>$result['result']['password'],'flag'=>'true');
        }
        else
        {
            return array('flag'=>'false');
        }
    }

    //查询余额
    public function checkBalance($accountUserName)
    {
        $url=self::API_HOST.self::API_PLAYER_BALANCE;
        $param=array(
            'playername'=>strtoupper($accountUserName)
        );
        $result =$this->ptCurl($url,$param);
        if($result&&isset($result['result']))
        {
            return array('balance'=>$result['result']['balance'],'flag'=>'success');
        }
        else
        {
            return array('flag'=>'false');
        }
    }

    //踢线
    public function kick($accountUserName)
    {
        $url=self::API_HOST.self::API_PLAYER_LOGOUT;
        $param=array(
            'playername' => strtoupper($accountUserName)
        );
        $result=$this->ptCurl($url,$param);
        if($result&&isset($result['result']['result']))
        {
            return array('flag'=>'true');
        }
        else
        {
            return array('flag'=>'false');
        }
    }

    //转入游戏帐号
    public function transferIn($accountUserName,$money)
    {
        $url=self::API_HOST.self::API_PLAYER_DEPOSIT;
        $param=array(
            'playername' => strtoupper($accountUserName),
            'amount'     => $money,
            'adminname'  => self::ADMINNAME
        );
        $result =$this->ptCurl($url,$param);
        if(isset($result['result']))
        {
            return array('flag'=>'true','orderNumber'=>$result['result']['ptinternaltransactionid'],'balance'=>$result['result']['currentplayerbalance']);
        }
        else if(!$result)
        {
            return array('flag'=>'unknown');
        }
        else
        {
             return array('flag'=>'false');
        } 
    }

    //转出游戏帐号
    public function transferOut($accountUserName,$money)
    {
        $this->kick($accountUserName);
        $url=self::API_HOST.self::API_PLAYER_WITHDRAW;
        $param=array(
            'playername' => strtoupper($accountUserName),
            'amount'     => $money,
            'adminname'  => self::ADMINNAME
        );
        $result =$this->ptCurl($url,$param);
        if($result&&isset($result['result']))
        {
            return array('flag'=>'true','orderNumber'=>$result['result']['ptinternaltransactionid'],'balance'=>$result['result']['currentplayerbalance']);
        }
        else if(!$result)
        {
            return array('flag'=>'unknown');
        }
        else
        {
            return array('flag'=>'false');
        }     
    }

    //进入pc版游戏
    public function playgamePc($param)
    {
        echo '<!doctype html><html lang="ch"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"><meta http-equiv="X-UA-Compatible" content="ie=edge"><title>登录中...</title></head><body>'
            .'<script type="text/javascript" src="https://login.greenjade88.com/jswrapper/integration.js.php?casino=greenjade88"></script>'
            .'<script type="text/javascript">'
            .'iapiSetCallout("Login", calloutLogin);'
            .'iapiLogin("'.$param['accountUserName'].'", "'.$param['password'].'", 1, "ch");'
            .'var requestId = iapiRequestIds[0][0];'
            .'function calloutLogin(response) {'
            .'if(response.errorCode == 0){'
            .'window.location.href = "http://cache.download.banner.greenjade88.com/casinoclient.html?language=ZH-CN&game='.$param['gamecode'].'";'
            .'}'
            .'}'
            .'</script>'
            .'</body></html>';
    } 

    //进入手机版游戏
    public function playgameMobile($param)
    {
        echo '<!doctype html><html lang="ch"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"><meta http-equiv="X-UA-Compatible" content="ie=edge"><title>登录中...</title></head><body>'
            .'<script type="text/javascript" src="https://login.greenjade88.com/jswrapper/integration.js.php?casino=greenjade88"></script>'
            .'<script type="text/javascript">'
            .'iapiSetCallout("Login", calloutLogin);'
            .'iapiLogin("'.$param['accountUserName'].'", "'.$param['password'].'", 1, "ch");'
            .'function calloutLogin(response) {console.log(response);askTempandLaunchGame();}'
            .'function getUrlVars() {var vars = {};var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {vars[key] = value;});return vars;}'
            .'iapiSetCallout("GetTemporaryAuthenticationToken", calloutGetTemporaryAuthenticationToken);'
            .'function askTempandLaunchGame() {var realMode = 1;iapiRequestTemporaryToken(realMode, 976, "GamePlay");}'
            .'function calloutGetTemporaryAuthenticationToken(response) {document.location = "https://hub.gm175788.com/igaming/?gameId='.$param['gamecode'].'&real=1&username='.$param['accountUserName'].'&lang=ZH-CN&tempToken=" + response.sessionToken.sessionToken;}'
            .'</script></body></html>';
    } 

    //试玩
    public function trial($array)
    {
         echo '<script> window.location="http://cache.download.banner.greenjade88.com/casinoclient.html?language=en&game=' .
                     $array['game_type'] . '&mode=offline&currency=cny"</script>';
    }

    //进入游戏大厅
    public function loginhall($param)
    {
        $this->playgamePc($param);
    }

    //进入手机版游戏大厅
    public function loginhallMobile($param)
    {
        $this->playgameMobile($param);
    }

    public function betRecord($starttime)
    {
        $url            =   self::API_HOST.self::API_PLAYER_GAMES_BETTING_FLOW;
        $endtime        =   $starttime+480;
        $starttime      =   urlencode(gmdate("Y-m-d H:i:s",$starttime+self::Gmt));
        $endtime        =   urlencode(gmdate("Y-m-d H:i:s",$endtime+self::Gmt));

        $param=array(
            'startdate'  => $starttime,
            'enddate'    => $endtime,
            'frozen'   => 'all'
        );
        $result = $this->ptCurl($url,$param);
        var_dump($result);

        return $result;

    }

}