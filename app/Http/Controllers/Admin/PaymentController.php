<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use App\Repositories\Admin\PaymentRepository;
use App\DataTables\Admin\PaymentDataTable;
use App\Http\Requests\Admin\CreatePaymentRequest;
use App\Models\Def\PayChannel;
use App\Models\Def\PayChannelType;
use Flash;
use Illuminate\Http\Request;

class PaymentController extends AppBaseController
{

    public $paymentRepos;

    public function __construct(PaymentRepository $paymentRepos)
    {
        $this->paymentRepos = $paymentRepos;
    }

    public function index(PaymentDataTable $paymentDatatable)
    {
        return $paymentDatatable->render('Admin.payment.index');
    }

    public function create()
    {
        $channelType = PayChannelType::where('parent_id', 0)->get();
        $info = PayChannelType::where('parent_id', 0)->first();
        $channelChild = PayChannelType::where('parent_id', $info->id)->get();
        return view('Admin.payment.create', compact('channelType','channelChild'));
    }

    public function store(CreatePaymentRequest $request)
    {
        $input = $request->all();
        if ($request->get('id')) {
            return $this->update($request->get('id'), $input);
        }
        try {
            $channel = PayChannel::where([
                'channel_name' => $input['channel_name'],
                'pay_channel_type_id' => $input['pay_channel_type_id']
            ])->first();
            if ($channel) {
                Flash::error('支付渠道已存在');
                
                return redirect()->back();
            }
            $payChannel = new PayChannel();
            $payChannel->fill($input);
            $payChannel->save();
        } catch (\Exception $e) {
            Flash::error('支付渠道添加失败');
            
            return redirect()->back();
        }
        
        Flash::success('支付渠道添加成功');
        
        return redirect(route('payments.index'));
    }

    public function edit($id)
    {
        $payChannel = $this->paymentRepos->with('payChannelType.parentPayChannelType.childInfo')->findWithoutFail($id);
        if (empty($payChannel)) {
            return $this->sendNotFoundResponse();
        }
        $channelType = PayChannelType::where('parent_id', 0)->get();
        return view('Admin.payment.edit', compact('payChannel', 'channelType'));
    }

    public function update(CreatePaymentRequest $request, $id)
    {
        $input = $request->all();
        $payChannel = $this->paymentRepos->findWithoutFail($id);
        
        if (empty($payChannel)) {
            return $this->sendNotFoundResponse();
        }
        try {
            $this->paymentRepos->update($input, $id);
        } catch (\Exception $e) {
            Flash::error('支付渠道修改失败');
            
            return redirect()->back();
        }
        Flash::success('支付渠道修改成功');
        
        return redirect(route('payments.index'));
    }

    public function getInfo(Request $request)
    {
        $payChannel = PayChannelType::where('parent_id', $request->input('id'))->get();

        if (empty($payChannel)) {

            Flash::error('支付类型不存在');

            return $this->sendError('支付类型不存在,请重试');
        }

        return $this->sendResponse($payChannel);

    }
}

