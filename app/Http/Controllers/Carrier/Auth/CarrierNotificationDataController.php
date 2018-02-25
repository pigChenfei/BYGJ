<?php
/**
 * Created by PhpStorm.
 * User: wugang
 * Date: 2017/4/27
 * Time: 下午5:11
 */

namespace App\Http\Controllers\Carrier\Auth;


use App\Helpers\Caches\CarrierInfoCacheHelper;
use App\Http\Controllers\AppBaseController;
use App\Notifications\CarrierAgentWithdrawNotification;
use App\Notifications\CarrierPlayerDepositNotification;
use App\Notifications\CarrierPlayerJoinActivityNotification;
use App\Notifications\CarrierPlayerWithdrawNotification;
use Carbon\Carbon;
use Illuminate\Notifications\DatabaseNotification;

class CarrierNotificationDataController extends AppBaseController
{

    public function index(){
        $notifications =  CarrierInfoCacheHelper::getCachedCarrierInfoByCarrierId(\WinwinAuth::carrierUser()->carrier_id)->unreadNotifications;
        $notifications = $notifications->map(function (DatabaseNotification $notification){
            $notifyFormatData = [
                'time' => $notification->created_at->toDateTimeString(),
                'notificationId' => $notification->id,
                'redirectRoute'  => null,
                'messageContent' => null,
                'textIconClass' => null,
            ];
            $data = $notification->data;
            if($notification->type == CarrierPlayerDepositNotification::class ){
                $notifyFormatData['textIconClass'] = 'fa fa-money text-aqua';
                $notifyFormatData['redirectRoute'] = route('playerDepositPayReviewLogs.index');
                $notifyFormatData['messageContent'] = '有新的存款申请,申请金额:'.$data['amount'].'元';
            }else if($notification->type == CarrierPlayerWithdrawNotification::class){
                $notifyFormatData['textIconClass'] = 'fa fa-inbox text-red';
                $notifyFormatData['redirectRoute'] = route('playerWithdrawLogs.verify');
                $notifyFormatData['messageContent'] = '有新的取款申请,申请金额:'.$data['apply_amount'].'元';
            }else if($notification->type == CarrierPlayerJoinActivityNotification::class){
                $notifyFormatData['textIconClass'] = 'fa fa-gamepad text-green';
                $notifyFormatData['redirectRoute'] = route('carrierActivityAudits.index');
                $notifyFormatData['messageContent'] = '有新的活动申请:'.$data['name'];
            }else if($notification->type == CarrierAgentWithdrawNotification::class){
                $notifyFormatData['textIconClass'] = 'fa fa-inbox text-red';
                $notifyFormatData['redirectRoute'] = route('carrierAgentWithdrawLogsVerify.index');
                $notifyFormatData['messageContent'] = '有新的取款申请,申请金额:'.$data['apply_amount'].'元';
            }
            return $notifyFormatData;
        });
        return  self::sendResponse($notifications, 'ok'); // \WinwinAuth::carrierUser()->notifications;
    }

    public function markAsReadNotification(\Illuminate\Http\Request $request){
        $this->validate($request,[
            'notificationIds' => 'required|array'
        ]);
        DatabaseNotification::whereIn('id',$request->get('notificationIds'))->update(['read_at' => Carbon::now()]);
    }

}