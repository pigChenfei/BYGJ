<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\AppBaseController;
use App\Models\Log\PlayerBetFlowLog;
use Illuminate\Http\Request;
use App\Models\CarrierAgentUser;
use App\Models\Player;
use App\Helpers\IP\RealIpHelper;
use App\Models\PlayerGameAccount;
use App\Jobs\PlayerInviteRewardHandle;
use App\Models\Log\PlayerDepositPayLog;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/11
 * Time: 10:15
 */
class TestController extends AppBaseController
{

    public $settlePeriod = '24';

    const KEY = '0B0R4P62F0TBL62PZZJFRPV00PZL440X';

    public function test($action, Request $request)
    {
        return call_user_func(array(
            $this,
            $action
        ), $request);
    }

    public function abc()
    {
        $time = time();
        $longminutetime = 5 * 60;
        $cipher = 'AES-256-ECB';
        // var_dump(openssl_get_cipher_methods());
        // exit();
        // $ivlen = openssl_cipher_iv_length($cipher);
        // $iv = openssl_random_pseudo_bytes($ivlen);
        $data = [
            "channelId" => - 1,
            'startTime' => gmdate("Y-m-d\TH:i:s\Z", $time - $longminutetime + 8 * 3600),
            'endTime' => gmdate("Y-m-d\TH:i:s\Z", $time + 8 * 3600),
            'state' => - 1,
            'recordPage' => 0,
            'recordCountPerPage' => 100
        ];
        try {
            $encData = openssl_encrypt(json_encode($data), $cipher, self::KEY, OPENSSL_RAW_DATA);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        $encData = base64_encode($encData);
        // $plain_data = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, self::KEY, $data, MCRYPT_MODE_ECB);
        
        // // Remove US-ASCII control character
        // $plain_data = trim($plain_data, "\x00..\x1F");
        $plain_data = openssl_decrypt(base64_decode($encData), $cipher, self::KEY, OPENSSL_RAW_DATA);
        echo $plain_data;
        exit();
    }

    public function mdfy()
    {
        $a = - 0.956;
        $b = 99.99;
        echo bcadd($a, $b, 2);
        echo "\t\n";
        echo abs(sprintf("%.2f", $a));
        echo "\t\n";
        echo round($a, 2);
    }

    public function singn(Request $request)
    
    {
        $log = $request->get('a');
        $depositpay = PlayerDepositPayLog::with('player')->where('pay_order_number', $log)->first();
        // $payment = PlayerDepositPayLog::with('carrierPayChannel.bindedThirdPartGateway')->where('carrier_id', 1)
        // ->where('pay_order_number', 'ply_1516353004311717')
        // ->first();
        $params = array(
            'merchantId' => '600000000000002',
            'merOrderId' => $depositpay->pay_order_number,
            'txnAmt' => $depositpay->amount * 100,
            'respCode' => 1001,
            'respMsg' => '交易成功'
        );
        $paramsNeedSign = $this->base64Npay($params);
        $signature = $this->signNpay($paramsNeedSign, '682807c82e2716452bd069aaf72d48dc');
        $params['signature'] = $signature;
        $url = route('pay.return', [
            'gateway' => 'npay',
            'order_id' => $depositpay->pay_order_number
        ]) . "?" . http_build_query($params);
        return redirect($url);
    }

    private function signNpay($params, $key)
    {
        ksort($params);
        $uri = urldecode(http_build_query($params));
        $uri = $uri . $key;
        $result = base64_encode(md5($uri, TRUE));
        return $result;
    }

    private function base64Npay($params, $decode = true)
    {
        $need_base64_fields = [
            'subject',
            'body'
        ];
        foreach ($need_base64_fields as $k) {
            if (array_key_exists($k, $params)) {
                if ($decode) {
                    $params[$k] = base64_decode($params[$k]);
                } else {
                    $params[$k] = base64_encode($params[$k]);
                }
            }
        }
        return $params;
    }

    public static function depositpay()
    {
        $depositpay = PlayerDepositPayLog::find(5)->toArray();
        
        $players = Player::where('agent_id', 2)->get();
        unset($depositpay['id']);
        foreach ($players as $player) {
            $depositpay['player_id'] = $player->id;
            $depositpay['pay_order_number'] = PlayerDepositPayLog::generatePayNumber();
            $depositpay['created_at'] = '2018-01-10 00:00:00';
            $depositpay['updated_at'] = '2018-01-10 00:00:00';
            $depositpay['credential'] = PlayerDepositPayLog::generateCredential();
            $depositpay['carrier_pay_channel'] = 1;
            PlayerDepositPayLog::firstOrCreate($depositpay);
        }
    }

    public static function queueDo()
    {
        $player = Player::find(100);
        dispatch(new PlayerInviteRewardHandle($player));
    }

    public static function playerBetFlow(Request $res)
    {
        $agentId = $res->get('a');
        $agent = CarrierAgentUser::where('id', $agentId)->first();
        $requestUrl = $res->header('origin');
        $recommendUrl = $requestUrl . '/homes.registerPage' . '?recommend_code=' . str_random(6);
        $shortened = Player::getShortUrl($recommendUrl);
        
        $input = array(
            'agent_id' => $agent->id,
            'player_level_id' => $agent->agentLevel->default_player_level,
            'referral_code' => Player::generateReferralCode(),
            'recommend_url' => $shortened,
            'pay_password' => bcrypt('000000'),
            'password' => bcrypt('123456'),
            'recommend_player_id' => 0,
            'carrier_id' => \WinwinAuth::currentWebCarrier()->id,
            'register_ip' => RealIpHelper::getIp()
        );
        $plIds = array();
        for ($i = 0; $i < 10; $i ++) {
            $input['user_name'] = str_random(8);
            $player = Player::firstOrCreate($input);
            $insert_arr = [
                'main_game_plat_id' => 1,
                'player_id' => $player->id,
                'account_user_name' => 'TTC' . str_random(8),
                'amount' => 100,
                'is_locked' => 0
            ];
            PlayerGameAccount::firstOrCreate($insert_arr);
            $plIds[] = $player;
        }
        
        foreach ($plIds as $pid) {
            $info = new PlayerBetFlowLog();
            $info->player_id = $pid->id;
            $info->carrier_id = $pid->carrier_id;
            $info->game_plat_id = 3;
            $info->game_id = 511;
            $info->game_flow_code = 73887934935;
            $info->game_status = 1;
            $info->bet_amount = 50;
            $info->company_win_amount = 50 - 10;
            $info->available_bet_amount = 50;
            $info->company_payout_amount = 40;
            $info->bet_flow_available = 1;
            $info->bet_info = '测试用';
            $info->progressive_bet = 0;
            $info->progressive_win = 0;
            $info->save();
        }
        
        return $info;
    }


    public function status(Request $request)
    {
        $input = $request->all();
        return view($request->input('views'),$input);
    }
}