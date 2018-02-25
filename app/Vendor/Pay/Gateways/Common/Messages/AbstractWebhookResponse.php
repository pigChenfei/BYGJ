<?php
namespace App\Vendor\Pay\Gateways\Common\Messages;

use App\Models\Player;

abstract class AbstractWebhookResponse extends AbstractResponse
{

    public $feedback;

    public function handle()
    {
        if (! empty($this->data)) {
            $player = Player::find($this->data->player_id);
            if ($this->data->status === 1) {
                $this->feedback = [
                    'success' => 200,
                    'fee_amount' => $this->data->fee_amount, // 手续费
                    'finally_amount' => $this->data->finally_amount, // 实际到账
                    'benefit_amount' => $this->data->benefit_amount, // 优惠金额
                    'bonus_amount' => $this->data->bonus_amount, // 红利金额
                    'amount' => $this->data->amount, // 充值金额
                    'status' => '支付成功',
                    'main_account_amount' => $player->main_account_amount // 主账户余额
                ];
            } else {
                $status = '';
                switch ($this->data->status) {
                    case - 1:
                        $status = '订单支付失败';
                        break;
                    case - 2:
                        $status = '审核未通过';
                        break;
                    case 2:
                        $status = '订单待审核';
                        break;
                }
                $this->feedback = [
                    'success' => 40001,
                    'fee_amount' => $this->data->fee_amount, // 手续费
                    'finally_amount' => $this->data->finally_amount, // 实际到账
                    'benefit_amount' => $this->data->benefit_amount, // 优惠金额
                    'bonus_amount' => $this->data->bonus_amount, // 红利金额
                    'amount' => $this->data->amount, // 充值金额
                    'status' => $status,
                    'main_account_amount' => $player->main_account_amount // 主账户余额
                ];
            }
        } else {
            $this->feedback = [
                'success' => 40002
            ];
        }
    }
}