<?php
/**
 * Created by PhpStorm.
 * User: winwin
 * Date: 2017/3/16
 * Time: 上午11:12
 */

namespace App\Vendor\GameGateway\TTG;

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

class TTG
{
   
    const AFFILIATEID       = 'TTC';

    const AFFILIATELOGIN    = 'TTC0988786';

    //测试URL
    //const API_URL           =  'https://ams2-api.stg.ttms.co:7443';

    //测试数据url
    const API_URL2         =    'https://ams-df.stg.ttms.co:7443/dataservice';

    //正式URL
    const API_URL           =  'https://ams2-api.stg.ttms.co:8443';

    //正式数据
    //const API_URL2         =    'https://ams2-df.ttms.co:8443/dataservice';

    //登录注册
    const API_LOGIN         =   self::API_URL.'/cip/gametoken/';

    //帐号存在判断
    const API_EXISTS        =   self::API_URL.'/cip/player/TTC/existence';

    //查询余额
    const API_BALANCE       =   self::API_URL.'/cip/player/TTC/balance';

    //转帐
    const API_TRANSATION    =   self::API_URL.'/cip/transaction/TTC/';

    //抓单
    const API_BETRECORD     =    self::API_URL2.'/datafeed/transaction/current';

    //详情抓单
    const API_BETRECORD1     =    self::API_URL2.'/datafeed/transaction/';

    //输赢报表
    const API_REPORT        =     self::API_URL2.'/datafeed/playernetwin/';


    private $lastSynchronizeTime;

    public static function registerRoutes(){
        return [
            //'ptLoginRedirect' => 'PTGameRedirectController@index'
        ];
    }

    public function ttgCurl($url,$param,$method='post')
    {
        $header   = array();
        $header[] = "Content-Type: application/xml"; 
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if($method=='post')
        {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,$param);   // post data
        }
        else if($method=='delete')
        {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
            curl_setopt($ch, CURLOPT_POSTFIELDS,$param);   // delete data
        }
        else
        {
            $str='';
            foreach($param as $key=>$v)
            {
                $str.=$key.'='.$v.'&';
            }
            $url =$url.'?'.rtrim($str,'&');
        }
        curl_setopt($ch, CURLOPT_URL, $url);

        $output = curl_exec($ch);
        $error =curl_error($ch);
        curl_close ($ch);
        if($error)
        {
            return false;
        }
        else
        {
            return $output;
        }
    }

     public function ttgdataCurl($url,$param,$method='post')
    {
        $header   = array();
        $header[] = "T24-Affiliate-Id: ".self::AFFILIATEID; 
        $header[] = "T24-Affiliate-Login: ".self::AFFILIATELOGIN; 
        $header[] = "Content-Type: text/xml"; 
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$param);   // post data
        curl_setopt($ch, CURLOPT_URL, $url);

        $output = curl_exec($ch);
        $error =curl_error($ch);
        curl_close ($ch);
        if($error)
        {
            return false;
        }
        else
        {
            return $output;
        }
    }

    public function betRecord()
    {
        $url =self::API_BETRECORD;
        $startDate ='20180123';
        $endDate   ='20180123';
        $requestId ='TTC_1Zr2KiB6';
        $param="<searchdetail requestId='".$requestId."'>"
              ."<daterange startDate='".$startDate."' startDateHour='13' startDateMinute='40' endDate='".$endDate."' endDateHour='13' endDateMinute='50'/>"
              ."<account currency='CNY' />"
              ."<partner partnerId='".self::AFFILIATEID."' includeSubPartner='Y' />"
              ."<transaction transactionType='Game'  />"
              ."</searchdetail>";

        return $this->ttgdataCurl($url,$param);
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
        $playeraccount='';
        $token='';
        do
        {
            $exist=0;
            $playeraccount=$pre.'Z'.$this->generate_value(6);
            $url=str_replace("TTC",$playeraccount,self::API_EXISTS);
            $param=array('uid'=>$playeraccount);
            $output =$this->ttgCurl($url,$param,'get');
            if($output)
            {
                $xmloutput=(array)simplexml_load_string($output);
                $exist =$xmloutput['@attributes']['exist'];
                if(!$exist)
                {
                    $token =$this->login($playeraccount);
                }
            }
        }while($exist);
        if($token)
        {
            return array('username'=>$playeraccount,'token'=>$token); 
        }
        else
        {
            return false;
        }
    }


    //登录用户
    public function login($accountUserName)
    {
        $url=self::API_LOGIN.$accountUserName;
        $param='<logindetail><player account="CNY" country="CN" firstName="" lastName="" userName="" nickName="" tester="0" partnerId="'.self::AFFILIATEID.'" commonWallet="0" /><partners><partner partnerId="zero" partnerType="0" /><partner partnerId="Everfriend" partnerType="1" /><partner partnerId="'.self::AFFILIATEID.'" partnerType="1" /></partners></logindetail>';
        $output =$this->ttgCurl($url,$param);
        if($output)
        {
            $xmloutput=(array)simplexml_load_string($output);
            if(isset($xmloutput['@attributes']['token']))
            {
                $token =$xmloutput['@attributes']['token'];
                return $token;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
        
    }

    //查询余额
    public function checkBalance($accountUserName)
    {
        $url=str_replace("TTC",$accountUserName,self::API_BALANCE);
        $param=array('uid'=>$accountUserName);
        $output =$this->ttgCurl($url,$param,'get');
        if($output)
        {
            $xmloutput=(array)simplexml_load_string($output);
            if(isset($xmloutput['@attributes']['real']))
            {
                return $xmloutput['@attributes']['real'];
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }

    //转入游戏平台，金额正负区分转入转出
    public function transferIn($accountUserName,$money)
    {
        $orederId='TTC_'.time().$this->generate_value(2);
        $url=self::API_TRANSATION.$orederId;
        $param='<transactiondetail uid="'.$accountUserName.'" amount="'.$money.'" />';
        $output =$this->ttgCurl($url,$param);
        if($output)
        {
            $xmloutput=(array)simplexml_load_string($output);
            if(isset($xmloutput['@attributes']['retry'])&&$xmloutput['@attributes']['retry']==0)
            {
                return array('transactionOrderId'=>$orederId,'success'=>true);
            }
            else
            {
                return array('transactionOrderId'=>$orederId,'success'=>false);
            }
        }
        else
        {
            return array('transactionOrderId'=>$orederId,'success'=>'unknown');
        }
    }

    //转出游戏平台，金额正负区分转入转出
    public function transferOut($accountUserName,$money)
    {
        return $this->transferIn($accountUserName,-$money);
    }

    //进入游戏
    public function playgameH5($token,$gamename,$gametype,$gameid)
    {
        $url='http://ams-games.stg.ttms.co/casino/default/game/game.html?playerHandle='.$token.'&account=CNY&gameName='.$gamename.'&gameType='.$gametype.'&gameId='.$gameid.'&lang=zh-cn&deviceType=web&lsdId=TTC';
        echo "<script>window.location.href ='".$url."'</script>";
    }

     /**
     * 更新同步时间到磁盘
     * @param $time
     */
    public function updateSynchronizedGameFlowTimeStamp($time){
        \Storage::put('TTG_synchronizedTimestamp',$time);
    }


//@longminute 抓取多少分钟的数据
    public function getGamesRecord($requestId,$start_time = null,$end_time = null,$page=0,$page_size=1000)
    {

        $starttime = strtotime($start_time);
        $endtime   = strtotime($end_time);
        $url =self::API_BETRECORD;

        $param="<searchdetail requestId='".$requestId."'>"
              ."<daterange startDate='".date('Ymd',$starttime)."' startDateHour='".date('H',$starttime)."' startDateMinute='".date('i',$starttime)."' endDate='".date('Ymd',$endtime)."' endDateHour='".date('H',$endtime)."' endDateMinute='".date('i',$endtime)."'/>"
              ."<account currency='CNY' />"
              ."<partner partnerId='".self::AFFILIATEID."' includeSubPartner='Y' />"
              ."<transaction transactionType='Game'  />"
              ."</searchdetail>";

        return $this->ttgdataCurl($url,$param);
    }
  /**
     * 获取游戏流水记录
     *
     * @param GameGatewaySearchCondition $condition            
     * @param PlayerGameAccount|null $gameAccount            
     * @return GameGatewayBetFlowRecord[]
     * @throws \Exception
     */
    public function fetchGameFlowResult(GameGatewaySearchCondition $condition, PlayerGameAccount $gameAccount = null)
    {
        try {
            $maingameplat       =MainGamePlat::where('main_game_plat_code',MainGamePlat::TTG)->first();
            $playerGameAccounts =PlayerGameAccount::where('main_game_plat_id',$maingameplat->main_game_plat_id)->get();
            foreach($playerGameAccounts as $playerGameAccount)
            {
                $result = $this->getGamesRecord($playerGameAccount->account_user_name,$condition->start_date, $condition->end_date); // 获取下注记录
                $xml =simplexml_load_string($result);
                $xmljson= json_encode($xml);
                $xml=json_decode($xmljson,true);
                if (isset($xml['details']['transaction'])) {
                    $flowRecord = array();
                    $i = 0;
                    $resultarray =$xml['details']['transaction']; 
                    foreach ($resultarray as $key => $element) 
                    {
                        $tempkey =$key+1;
                        if($key%2==0)
                        {
                            $flowRecord[$i]['playerName'] = $playerGameAccount->account_user_name;
                            $flowRecord[$i]['bet'] = abs($element['detail']['@attributes']['amount']);
                            $flowRecord[$i]['win'] = $resultarray[$tempkey]['detail']['@attributes']['amount'];
                            $flowRecord[$i]['company_win_amount'] = $flowRecord[$i]['bet']-$flowRecord[$i]['win'];
                            $flowRecord[$i]['status'] = 1;
                            $flowRecord[$i]['date'] = $element['detail']['@attributes']['transactionDate'];
                            $flowRecord[$i]['code'] = $element['detail']['@attributes']['transactionId'];
                            $flowRecord[$i]['gameCode'] = $element['detail']['@attributes']['game']; // 游戏代码
                            $flowRecord[$i]['gameName'] = $element['detail']['@attributes']['game']; // 游戏代码
                            $flowRecord[$i]['game_id'] =  $element['detail']['@attributes']['game'];
                            $flowRecord[$i]['isBetAvailable'] = true; 
                            $flowRecord[$i]['availableBet'] = abs($element['detail']['@attributes']['amount']);
                            \WLog::info('数据'.json_encode($element['detail']['@attributes']));
                            $i ++;
                        }
                    }
                    return $flowRecord;
                }
            }
            return [];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function lastSynchronizedGameFlowTimeStamp()
    {
        //获取最后同步的时间, 如果没有时间, 说明是初始化同步. 那么从持久化记录里面获取上次的保存时间;
        if(!$this->lastSynchronizeTime && \Storage::exists('TTG_synchronizedTimestamp')){
            $this->lastSynchronizeTime = \Storage::get('TTG_synchronizedTimestamp');
        }
        //如果上次的时间在28(接近30)分钟之前, 那么我们将时间强制减到20分钟 或者 如果最后还是没有时间,那么确实是第一次, 那就初始化时间为20分钟之前;
        if(!$this->lastSynchronizeTime || $this->lastSynchronizeTime <= date('Y-m-d H:i:s',time() - 10 * 60)){
            //如果最后还是没有时间,那么确实是第一次, 那就初始化时间为20分钟之前;
            $this->lastSynchronizeTime = date('Y-m-d H:i:s',time() - 5 * 60);
        }
        return $this->lastSynchronizeTime;
    }

     // 数据同步保存到硬盘上
    public function synchronizeGameFlowToDB(GameGatewaySearchCondition &$condition, 
        PlayerGameAccount $gameAccount = null)
    {
        try {
            $betFlowRecords = $this->fetchGameFlowResult($condition, $gameAccount);
        } catch (\Exception $e) {
            // 需要将下次同步的时间加上一秒
            $condition->end_date = Carbon::createFromFormat('Y-m-d H:i:s', $condition->end_date)->addSeconds(1)->toDateTimeString();
            throw $e;
        }
        if (! $betFlowRecords) {
            // 需要将下次同步的时间加上一秒
            $condition->end_date = Carbon::createFromFormat('Y-m-d H:i:s', $condition->end_date)->addSeconds(1)->toDateTimeString();
            return [];
        }
        $lastSynchronizeTime = null;
        foreach ($betFlowRecords as $record) {
            try {
                $playerBetFlow = new PlayerBetFlowLog();
                $playerGameAccount = PlayerGameAccount::getCachedPlayerGameAccountByPlayerName($record['playerName']);
                
                if (! $playerGameAccount) {
                    throw new GameGateWayRuntimeException('该会员无法找到相应的游戏账户:' . $record['playerName']);
                }
                
                $game = Game::getCachedGameByGameTyep($record['gameCode']);
                /* 这里需要先录入游戏到数据库中然后在查询 */
                if (! $game) {
                    $noneExists = array(
                        'game_plat' => 'SUNBET',
                        'game_flow_code' => $record['code'],
                        'game_code' => $record['gameCode'],
                        'game_name' => $record['gameName'],
                        'message' => '无法找到游戏:' . $record['gameName']
                    );
                    
                    try {
                        $exists = \DB::table('log_game_none_exixts')->where(
                            [
                                'game_plat' => $noneExists['game_plat'],
                                'game_code' => $record['gameCode']
                            ])->first();
                        if (! $exists) {
                            \DB::insert(
                                "insert into `log_game_none_exixts` (`game_plat`,`game_flow_code`,`game_code`,`game_name`,`message`,`created_at`,`updated_at`) value (?,?,?,?,?,now(),now())", 
                                array_values($noneExists));
                        }
                    } catch (\PDOException $e) {
                        throw $e;
                    }
                    throw new GameGateWayRuntimeException('无法找到游戏:' . $record['gameCode']);
                }
                
                $playerBetFlow->player_id = $playerGameAccount->player_id;
                // 查询到会员id即可查询到carrier_id;
                $playerBetFlow->carrier_id = $playerGameAccount->player->carrier_id;
                
                $playerBetFlow->game_id = $game->game_id;
                $playerBetFlow->game_plat_id = $game->game_plat_id; // 这里占时先写一个固定的值
                $playerBetFlow->game_flow_code = $record['code'];
                $playerBetFlow->bet_amount = $record['bet'];
                $playerBetFlow->company_payout_amount = $record['win'];
                $playerBetFlow->company_win_amount = $record['company_win_amount']; // 净投注额
                $playerBetFlow->bet_flow_available = $record['isBetAvailable'];
                $playerBetFlow->available_bet_amount = $record['availableBet'];
                $playerBetFlow->player_or_banker = 0;
                $playerBetFlow->created_at = strtotime($record['date']);
                $playerBetFlow->game_status = $record['status'];
                $playerBetFlow->bet_info = '';
                
                if (! $lastSynchronizeTime || $lastSynchronizeTime < $record['date']) {
                    $lastSynchronizeTime = $record['date'];
                }
                $condition = json_decode(json_encode($playerBetFlow), true);
                \WLog::info('====> sunbet入库开始', $condition);
                $findData = PlayerBetFlowLog::where(
                    [
                        'game_flow_code' => $playerBetFlow->game_flow_code,
                        'game_id' => $playerBetFlow->game_id,
                        'game_plat_id' => $playerBetFlow->game_plat_id,
                        'player_id' => $playerBetFlow->player_id,
                        'carrier_id' => $playerBetFlow->carrier_id
                    ])->first();
                if (empty($findData)) { // 数据不存在，保存数据
                    \WLog::info('====> sunbet 入库');
                    $playerBetFlow->save();
//                    \DB::insert(
//                        "insert into `log_player_bet_flow` (" . implode(",", array_keys($condition)) . ",`updated_at`)
//                        value (?,?,?,?,?,?,?,?,?,?,?,?,?,?,now())",
//                        array_values($condition));
                } else {
                    $findData->fill($condition);
                    $findData->save();
                }
                
                \WLog::info('====>会员投注记录同步成功:', 
                    [
                        'game_flow_code' => $playerBetFlow->game_flow_code,
                        'date' => $record['date']
                    ]);
            } catch (\Exception $e) {
                \WLog::error('====>会员投注记录同步失败:', [
                    'message' => $e->getMessage()
                ]);
                throw $e;
            }
        }
        $pos = strpos($lastSynchronizeTime, '.');
        if ($pos) { // 时间字符串中包含秒数
            $lastSynchronizeTime = substr($lastSynchronizeTime, 0, $pos);
        }
        $condition->end_date = Carbon::createFromFormat('Y-m-d H:i:s', $lastSynchronizeTime)->addSeconds(1)->toDateTimeString();
        return $betFlowRecords;
    }
    
}