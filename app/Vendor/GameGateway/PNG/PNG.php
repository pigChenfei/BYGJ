<?php
/**
 * Created by PhpStorm.
 * User: winwin
 * Date: 2017/3/16
 * Time: 上午11:12
 */

namespace App\Vendor\GameGateway\PNG;

use App\Models\PlayerGameAccount;
use App\Models\Def\MainGamePlat;
use App\Models\Def\Game;
use App\Models\Log\PlayerAccountLog;
use App\Http\Controllers\Web\GameController;
use App\Models\Log\PlayerBetFlowLog;
use App\Models\Player;
use App\Vendor\GameGateway\Gateway\Exception\GameGateWayRuntimeException;
use App\Vendor\GameGateway\Gateway\GameGatewayBetFlowRecord;
use App\Vendor\GameGateway\Gateway\GameGatewaySearchCondition;
use Carbon\Carbon;
use App\Models\PlayerTransfer;
use Models\Log\GameNoneExists;
use App\Vendor\GameGateway\Gateway\Exception\GameGateWaySynchronizeDBException;
use Curl\Curl;

class PNG
{
    //API用户名
    const API_USERNAME ='TTCCNYAPI';

    //API密码
    const API_PASSWORD ='12345678';

    //APItoken,postman生成
    const API_TOKEN   ='Basic VFRDQ05ZQVBJOjEyMzQ1Njc4';

    //正式：http://api.gmaster8.com,测试：http://api.dynastyggroup.com

    const API_URL='https://api.gmaster8.com';

    //注册
    const API_REGISTER          =  self::API_URL.'/register';

    //激活玩家
    const API_ACTIVE            =   self::API_URL.'/PNG/player/active';

    //获取玩家余额
    const API_BALANCE           =   self::API_URL.'/PNG/player/balance';

    //存款
    const API_DEPOSIT           =   self::API_URL.'/PNG/credit/deposit';

    //取款
    const API_WITHDRAWAL        =   self::API_URL.'/PNG/credit/withdrawal';

    //执行游戏
    const API_OPEN              =   self::API_URL.'/PNG/game/open';

    //强行关闭游戏
    const API_CLOSE             =   self::API_URL.'/PNG/game/close';

    //锁定玩家
    const API_LOCK              =   self::API_URL.'/player/lock';

    //解锁玩家
    const API_UNLOCK            =   self::API_URL.'/player/unlock';

    //交易状态查询
    const API_CHECK_TRANSACTION =   self::API_URL.'/PNG/credit/check_transaction';

    //游戏记录查询
    const API_HISTORY           =   self::API_URL.'/PNG/game/history';

    //修改玩家平台密码
    const API_CHANGE_PASSWORD   =   self::API_URL.'/PNG/player/change_password';

    //获取当前平台总余额
    const API_TOTAL_BALANCE     =   self::API_URL.'/PNG/total_balance';

    private $lastSynchronizeTime;

    public static function registerRoutes(){
        return [
            //'ptLoginRedirect' => 'PTGameRedirectController@index'
        ];
    }
    public function pngCurl($url,$param)
    {
        $header   = array();
        $header[] = "Accept:text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8"; 
        $header[] = "Cache-Control: max-age=0"; 
        $header[] = "Connection: keep-alive"; 
        $header[] = "Keep-Alive:timeout=5, max=100"; 
        $header[] = "Accept-Charset:ISO-8859-1,utf-8;q=0.7,*;q=0.3"; 
        $header[] = "Accept-Language:es-ES,es;q=0.8"; 
        $header[] = "Pragma: "; 
        $header[] = "Authorization:" . self::API_TOKEN;
        $header[] = "username:" . self::API_USERNAME;
        $header[] = "password:" . self::API_PASSWORD;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($param));   // post data
        //echo http_build_query($param);exit;
        $output = curl_exec($ch);
        $error =curl_error($ch);
        curl_close ($ch);
        if($error)
        {
            return false;
        }
        else
        {
            return json_decode($output,true);
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

    //创建用户
    public function createMember($pre)
    {
        $playeraccount=$pre.'Z'.$this->generate_value(6);
        $url=self::API_REGISTER;
        $password = $this->generate_value(6);
        $param=array(
            'username'=>$playeraccount,
            'password'=>$password
        );
        $output = $this->pngCurl($url,$param);
        if($output&&isset($output['player_name']))
        {
            return array('username'=>$output['player_name'],'password'=>$password);
        }
        else
        {
            return false;
        }
    }

    //登录
    public function login($username,$plat='PNG')
    {
        $url=self::API_ACTIVE;
        if($plat!='PNG')
        {
            $url=str_replace('PNG',$plat,$url);
        }
        $param=array(
            'username'=>$username
        );
        $output = $this->pngCurl($url,$param);
        if($output&&isset($output['status'])&&$output['status']=='success')
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    //获取玩家余额
    public function checkBalance($username,$plat='PNG')
    {
        $url=self::API_BALANCE;
        if($plat!='PNG')
        {
            $url=str_replace('PNG',$plat,$url);
        }
        $param=array(
            'username'=>$username
        );
        $output = $this->pngCurl($url,$param);
        if($output&&isset($output['balance']))
        {
            return $output['balance'];
        }
        else
        {
            return false;
        }
    }

    //转入金额
    public function transferIn($accountUserName,$money,$plat='PNG')
    {
        $url=self::API_DEPOSIT;
        $externalTransactionId =time().$this->generate_value(6);
        if($plat!='PNG')
        {
            $url=str_replace('PNG',$plat,$url);
        }
        $param=array(
            'username'=>$accountUserName,
            'amount'=>$money,
            'externalTransactionId'=>$externalTransactionId
        );
        $output =$this->pngCurl($url,$param);
        if($output&&isset($output['ending_balance']))
        {
            return array('balance'=>$output['ending_balance'],'transferOrderid'=>$output['transaction_id'],'flag'=>true);
        }
        else if(!$output)
        {
             return array('transferOrderid'=>$output['transaction_id'],'flag'=>'unknown');
        }
        else
        {
            return array('transferOrderid'=>$externalTransactionId,'flag'=>false);
        }
    }

    //  抓单
    public function betRecord($plat='PNG')
    {
        $url=self::API_HISTORY;
        if($plat!='PNG')
        {
            $url=str_replace('PNG',$plat,$url);
        }
        $param=array(
            'fromDate'=>date("Y-m-d\TH:i:s", time()-360),
            'totoDate'=>date("Y-m-d\TH:i:s", time())
        );
        var_dump($url);
        dump($param);
        $output =$this->pngCurl($url,$param);
        return $output;
    }
    //转出金额
    public function transferOut($accountUserName,$money,$plat='PNG')
    {
        $url=self::API_WITHDRAWAL;
        $externalTransactionId =time().$this->generate_value(6);
        if($plat!='PNG')
        {
            $url=str_replace('PNG',$plat,$url);
        }
        $param=array(
            'username'=>$accountUserName,
            'amount'=>$money,
            'externalTransactionId'=>$externalTransactionId
        );
        $output =$this->pngCurl($url,$param);
        if($output&&isset($output['ending_balance']))
        {
            return array('balance'=>$output['ending_balance'],'transferOrderid'=>$output['transaction_id'],'flag'=>true);
        }
        else if(!$output)
        {
             return array('transferOrderid'=>$output['transaction_id'],'flag'=>'unknown');
        }
        else 
        {
            return array('transferOrderid'=>$externalTransactionId,'flag'=>false);
        }
    }

    public function playgameH5($accountUserName,$game_code,$plat='PNG',$clienttype='flash')
    {
        $url=self::API_OPEN;
        if($plat!='PNG')
        {
            $url=str_replace('PNG',$plat,$url);
        }
        $param=array(
            'username'=>$accountUserName,
            'game_code'=>$game_code,
            'lang'=>'zh-cn'
        );
        $output =$this->pngCurl($url,$param);
        if($output&&isset($output['ticket']))
        {
            $str ='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'; 
            $str.='<html xmlns="http://www.w3.org/1999/xhtml">'; 
            $str.='<head><title></title>';  
            $str.='<script src="https://bsicw.playngonetwork.com/Casino/js?div=pngCasinoGame&gid='.$game_code.'&lang=zh-cn&pid=314&username='.$output['ticket'].'&practice=0&width=1024px&height=768px" type="text/javascript"></script>';
            $str.='</head> <body >'; 
            $str.='<div id="pngCasinoGame" width="100%" height="100%"></div>';  
            $str.='</body></html>';
            echo $str;
        }
    }

    public function playmobilegameH5($accountUserName,$game_code,$plat='PNG',$clienttype='flash')
    {
        $url=self::API_OPEN;
        if($plat!='PNG')
        {
            $url=str_replace('PNG',$plat,$url);
        }
        $param=array(
            'username'=>$accountUserName,
            'game_code'=>$game_code,
            'lang'=>'zh-cn'
        );
        $output =$this->pngCurl($url,$param);
        if($output&&isset($output['ticket']))
        { 
            $str="https://bsicw.playngonetwork.com/casino/PlayMobile?pid=314&gid=".$game_code."&lang=zh_CN&ticket=".$output['ticket']."&practice=0";
            echo "<script>window.location.href ='".$str."'</script>";
        }
    }
    /**
     * 更新同步时间到磁盘
     * @param $time
     */
    public function updateSynchronizedGameFlowTimeStamp($time){
        \Storage::put('PNG_synchronizedTimestamp',$time);
    }

    public function lastSynchronizedGameFlowTimeStamp()
    {
        //获取最后同步的时间, 如果没有时间, 说明是初始化同步. 那么从持久化记录里面获取上次的保存时间;
        if(!$this->lastSynchronizeTime && \Storage::exists('PNG_synchronizedTimestamp')){
            $this->lastSynchronizeTime = \Storage::get('PNG_synchronizedTimestamp');
        }
        //如果上次的时间在28(接近30)分钟之前, 那么我们将时间强制减到20分钟 或者 如果最后还是没有时间,那么确实是第一次, 那就初始化时间为20分钟之前;
        if(!$this->lastSynchronizeTime || $this->lastSynchronizeTime <= date('Y-m-d H:i:s',time() - 10 * 60)){
            //如果最后还是没有时间,那么确实是第一次, 那就初始化时间为20分钟之前;
            $this->lastSynchronizeTime = date('Y-m-d H:i:s',time() - 5 * 60);
        }
        return $this->lastSynchronizeTime;
    }
//@longminute 抓取多少分钟的数据
    public function getGamesRecord($start_time = null,$end_time = null,$page=0,$page_size=1000)
    {
        $url=self::API_HISTORY;
        $param=array(
            'fromDate'=>$start_time,
            'toDate'=>$end_time
        );

        $output =$this->pngCurl($url,$param);
        return $output;
    }

public function fetchGameFlowResult(GameGatewaySearchCondition $condition, PlayerGameAccount $gameAccount = null,$page)
    {
        //$start_time = str_replace(' ','T',$condition->start_date).'Z' ;

        //$end_time = str_replace(' ','T',$condition->end_date).'Z' ;
        $start_time=date('Y-m-d\TH:i:s',strtotime($condition->start_date));
        $end_time=date('Y-m-d\TH:i:s',strtotime($condition->end_date));

        /*$start_time = str_replace(' ','T',date("Y-m-d H:i:s",time()-3600*10000)) ; //当前时间1000小时前的时间
        $end_time = str_replace(' ','T',date("Y-m-d H:i:s",time())) ; //当前时间*/
        try{
            $response = $this ->getGamesRecord($start_time,$end_time,$page);
            \WLog::info('====>进入png同步数据'.json_encode($response));
            $dataArr = $response ;
            if($dataArr){
                $flowRecord = array() ;
                $i = 0 ;
                //var_dump($flowRecord) ; echo "print_data:<br>" ;
                foreach ($dataArr as $element) {
                    \WLog::info('====>进入循环组装数据');
                    $flowRecord[$i]['playerName'] = $element['player_name'];
                    $flowRecord[$i]['gameCode']   = $element['game_id']; //游戏代码
                    $flowRecord[$i]['bet']        = $element['bet'];
                    $flowRecord[$i]['win']        = $element['win'];
                    $flowRecord[$i]['status']        = 1 ;
                    $flowRecord[$i]['date']       = $element['time'];
                    $flowRecord[$i]['plat_game_code']       = $element['game_id'];
                    $flowRecord[$i]['code']       = $element['transaction_id']; //游戏流水号
                    $flowRecord[$i]['game_id']       = $element['game_id'];
                    $flowRecord[$i]['isBetAvailable'] =  true; //有效投注
                    $flowRecord[$i]['availableBet']  = $element['bet'] ; //有效投注额

                    $i ++ ;
                }
                \WLog::info('====>进入fetchGameFlowResult组装数据'.json_encode($flowRecord));
                return $flowRecord ;
            }
            return [];
        }catch (\Exception $e){
            throw $e;
        }
    }
    /**
     * @param GameGatewayBetFlowRecord[] $betFlowRecords
     *///数据同步保存到硬盘上
    public function synchronizeGameFlowToDB(GameGatewaySearchCondition &$condition, PlayerGameAccount $gameAccount = null) {
        \WLog::info('====>进入Png数据同步开始');
       $page = 0 ;
        do{ //循环查询数据
            $page ++ ; //查询的第几页的数据
            try{
                \WLog::info('====>Png数据同步开始');
                $betFlowRecords = $this->fetchGameFlowResult($condition,$gameAccount,$page);
                if (empty($betFlowRecords)) { //数据为空
                    break ;
                }
                //return ;
            }catch (\Exception $e){
                //需要将下次同步的时间加上一秒
                $condition->end_date = Carbon::createFromFormat('Y-m-d H:i:s',$condition->end_date)->addSeconds(1)->toDateTimeString();
                throw $e;
            }
            if(!$betFlowRecords){
                \WLog::info('====>暂无游戏同步数据');
                //需要将下次同步的时间加上一秒
                $condition->end_date = Carbon::createFromFormat('Y-m-d H:i:s',$condition->end_date)->addSeconds(1)->toDateTimeString();
                return [];
            }
            $lastSynchronizeTime = null;
            foreach ($betFlowRecords as $record){
                try{
                    $playerBetFlow = new PlayerBetFlowLog();
                    $playerGameAccount = PlayerGameAccount::getCachedPlayerGameAccountByPlayerName($record['playerName']);
                    if(!$playerGameAccount){
                        throw new GameGateWayRuntimeException('该会员无法找到相应的游戏账户:'.$record['playerName']);
                    }
                    $playerBetFlow->player_id = $playerGameAccount->player_id;
                    //查询到会员id即可查询到carrier_id;
                    $playerBetFlow->carrier_id = $playerGameAccount->player->carrier_id;
                    $GameObj = new Game();
                    $game = $GameObj->where('game_code',$record['plat_game_code']) ->first() ;
                    if(!$game){
                        throw new GameGateWayRuntimeException('无法找到游戏码:'.$record['gameCode']." 对应的游戏");
                    }

                    $playerBetFlow->game_id = $game['game_id'];

                    //游戏平台id，这里现在是写的假数据，后面需要修改 add by tlt
                    $playerBetFlow->game_plat_id = $game->game_plat_id;
                    $playerBetFlow->game_flow_code = $record['code'];
                    $playerBetFlow->bet_amount = $record['bet'];
                    $playerBetFlow->company_payout_amount = $record['win'];
                    $playerBetFlow->company_win_amount    = $record['bet'] - $record['win'];
                    $playerBetFlow->bet_flow_available  = $record['isBetAvailable'];
                    $playerBetFlow->available_bet_amount = $record['availableBet'];
                    // $playerBetFlow->player_or_banker = $record->playerOrBanker;
                    $playerBetFlow->created_at = strtotime($record['date']);

                    $playerBetFlow->game_type = $record['gameCode'];
                    $playerBetFlow->game_status = $record['status'];

                    if(!$lastSynchronizeTime || $lastSynchronizeTime < $record['date']){
                        $lastSynchronizeTime = $record['date'];
                    }
                    \WLog::info('====>相关数据是'.json_encode($playerBetFlow));
                    \DB::transaction(function () use ($playerGameAccount,$playerBetFlow){
                        $condition = json_decode(json_encode($playerBetFlow),true) ;
                        $findData = PlayerBetFlowLog::where($condition) ->first() ;
                        if (empty($findData)) { //数据不存在，保存数据
                            $res = $playerBetFlow->save();   
                        }
                    });

                    \WLog::info('====>会员投注记录同步成功:',['game_flow_code' => $playerBetFlow->game_flow_code,'date' => $record['date']]);
                }catch (\Exception $e){
                    //var_dump('1114443333555') ;
                    \WLog::error('====>会员投注记录同步失败:',['message' => $e->getMessage()]);
                    throw $e;
                }
            }
            $pos = strpos($lastSynchronizeTime,'.') ;
            if ($pos) { //时间字符串中包含秒数,过滤秒数
                $lastSynchronizeTime = substr($lastSynchronizeTime,0,$pos) ;
            }
            $condition->end_date = Carbon::createFromFormat('Y-m-d H:i:s',$lastSynchronizeTime)->addSeconds(1)->toDateTimeString();
        } while(1) ;
        return $betFlowRecords;
    }
}