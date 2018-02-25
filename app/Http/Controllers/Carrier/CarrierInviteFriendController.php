<?php
/**
 * Created by PhpStorm.
 * User: wugang
 * Date: 2017/4/18
 * Time: 下午7:46
 */

namespace App\Http\Controllers\Carrier;


use App\Http\Controllers\AppBaseController;
use App\Models\Conf\CarrierInvitePlayerConf;
use Illuminate\Http\Request;

class CarrierInviteFriendController extends AppBaseController
{

    public function showEdit(){
        $invitePlayerConf = \WinwinAuth::carrierUser()->carrier->invitePlayerConf;
        if(!$invitePlayerConf){
            $invitePlayerConf = new CarrierInvitePlayerConf();
            $invitePlayerConf->carrier_id = \WinwinAuth::carrierUser()->carrier_id;
            $invitePlayerConf->bet_reward_rule = json_encode([]);
            $invitePlayerConf->deposit_reward_rule = json_encode([]);
            $invitePlayerConf->saveOrFail();
        }
        return view('Carrier.invite_friend_conf.index')->with('invitePlayerConf',$invitePlayerConf);
    }

    public function saveInvitePlayerConf(Request $request){
        $this->validate($request,[
            'bet_reward_settle_period' => 'required|in:'.CarrierInvitePlayerConf::SETTLE_PERIOD_WEEK.','.CarrierInvitePlayerConf::SETTLE_PERIOD_DAY,
            'deposit_reward_settle_period' => 'required|in:'.CarrierInvitePlayerConf::SETTLE_PERIOD_WEEK.','.CarrierInvitePlayerConf::SETTLE_PERIOD_DAY,
            'bet_reward_rule' => 'required|json',
            'deposit_reward_rule' => 'required|json',
            'invalid_player_bet_amount' => 'required|numeric|min:0',
            'invalid_player_deposit_amount' => 'required|numeric|min:0'
        ]);
        $betRewardRule = json_decode($request->get('bet_reward_rule'),true);
        foreach ($betRewardRule as $rule){
            if(!isset($rule['availableBetAmount']) || !isset($rule['playerRewardPercent']) || !isset($rule['playerRewardMax']) || !isset($rule['invitePlayerRewardPercent']) || !isset($rule['invitePlayerRewardMax'])){
                return $this->sendErrorResponse('投注额奖励规则数据不完整');
            }
        }
        $depositRewardRule = json_decode($request->get('deposit_reward_rule'),true);
        foreach ($depositRewardRule as $rule){
            if(!isset($rule['depositAmount']) || !isset($rule['playerRewardAmount']) || !isset($rule['invitePlayerRewardAmount'])){
                return $this->sendErrorResponse('存款额奖励规则数据不完整');
            }
        }
        \WinwinAuth::carrierUser()->carrier->invitePlayerConf->update($request->all());
        return $this->sendSuccessResponse();
    }

}