<?php
namespace App\Http\Controllers\Payment;

use App\Vendor\Pay\PayGateway;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Models\Log\PlayerDepositPayLog;
use App\Http\Controllers\AppBaseController;

class CheckoutController extends AppBaseController
{

    //
    public function webhook(Request $request, $gateway, $order_id = null)
    {
        Log::info($order_id);
        try {
            $pay_gateway = PayGateway::gateway($gateway)->webhook($order_id);
        } catch (Exception $e) {
            \Log::error('订单异步通知异常',
                [
                    $gateway . '订单：' . $order_id . '异步通知异常' => $e->getMessage()
                ]);
        }
    }

    public function returnNotify(Request $request, $gateway, $order_id = null)
    {
        \Log::info($order_id);
        try {
            $pay_gateway = PayGateway::gateway($gateway)->webhook($order_id);
        } catch (Exception $e) {
            \Log::error('订单同步通知异常',
                [
                    $gateway . '订单：' . $order_id . '同步通知异常' => $e->getMessage()
                ]);
        }
        $img = 'payerror';
        $icon = 'icon-payfail';
        $title = '存款失败';
        $data = array();
        if (! empty($pay_gateway) && ! empty($pay_gateway->feedback) && $pay_gateway->feedback['success'] == 200) {
            $img = '';
            $icon = 'icon-paysuccess';
            $title = '存款成功';
            $data = $pay_gateway->feedback;
        } elseif (! empty($pay_gateway) && ! empty($pay_gateway->feedback) && $pay_gateway->feedback['success'] == 40001) {
            $data = $pay_gateway->feedback;
        }
        return \WTemplate::notifyPage()->with('img', $img)
            ->with('title', $title)
            ->with('icon', $icon)
            ->with('data', $data);
    }
}
