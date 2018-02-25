<?php

namespace App\Http\Controllers\Carrier;

use App\DataTables\Carrier\CarrierPayChannelDataTable;
use App\Exceptions\CarrierAccountException;
use App\Http\Requests\Carrier;
use App\Http\Requests\Carrier\CreateCarrierPayChannelRequest;
use App\Http\Requests\Carrier\UpdateCarrierPayChannelRequest;
use App\Models\CarrierPayChannel;
use App\Models\Log\CarrierQuotaConsumptionLog;
use App\Models\Log\PlayerAccountLog;
use App\Repositories\Carrier\CarrierPayChannelRepository;
use App\Models\Def\PayChannelType;
use App\Repositories\Carrier\PayChannelTypeRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;
use Illuminate\Http\Request;

class CarrierPayChannelController extends AppBaseController
{
    /** @var  CarrierPayChannelRepository */
    private $carrierPayChannelRepository;

    public function __construct(CarrierPayChannelRepository $carrierPayChannelRepo)
    {
        $this->carrierPayChannelRepository = $carrierPayChannelRepo;
    }

    /**
     * Display a listing of the CarrierPayChannel.
     *
     * @param CarrierPayChannelDataTable $carrierPayChannelDataTable
     * @return Response
     */
    public function index(CarrierPayChannelDataTable $carrierPayChannelDataTable)
    {
        return $carrierPayChannelDataTable->render('Carrier.carrier_pay_channels.index');
    }

    /**
     * Show the form for creating a new CarrierPayChannel.
     *
     * @return Response
     */
    public function create()
    {
        $payChannelType = PayChannelType::where('parent_id', 0)->get();
        $parentPayChannelType = PayChannelType::where('parent_id', $payChannelType[0]['id'])->get();
        $payChannelList = \App\Models\Def\PayChannel::where('pay_channel_type_id', $parentPayChannelType[0]['id'])->get();

        $data['image_category'] = 5;
        $img = \App\Models\Image\CarrierImage::where($data)->get();

        return view('Carrier.carrier_pay_channels.create')->with(['payChannelType' => $payChannelType, 'parentPayChannelType' => $parentPayChannelType, 'payChannelList' => $payChannelList, 'img' => $img]);
    }

    /**
     * Store a newly created CarrierPayChannel in storage.
     *
     * @param CreateCarrierPayChannelRequest $request
     *
     * @return Response
     */
    public function store(CreateCarrierPayChannelRequest $request)
    {
        $input = $request->all();
        if (!empty($request->get('def_pay_channel_id'))) {
            $pay = \App\Models\Def\PayChannel::where('id', $request->get('def_pay_channel_id'))->first();
            $parent = PayChannelType::where('id', $pay['pay_channel_type_id'])->first();
            if ($parent['id'] == PayChannelType::SCAN_CODE_COMPANY_PAY) {
                if (!empty($request->get('qrcode'))) {
                    $input['qrcode'] = $request->get('qrcode');
                } else {
                    $error_respon = array('success' => false, 'message' => '二维码不能为空');
                    return $error_respon;
                }
            } else {
                $input['qrcode'] = 0;
            }
        }
        if (!empty($request->get('fee_ratio'))) {
            $input['fee_ratio'] = $request->get('fee_ratio');
        } else {
            $input['fee_ratio'] = 0;
        }
        if (!empty($request->get('default_preferential_ratio'))) {
            $input['default_preferential_ratio'] = $request->get('default_preferential_ratio');
        } else {
            $input['default_preferential_ratio'] = 0;
        }
        $input['carrier_id'] = \Auth::user()->carrier_id;
        $this->carrierPayChannelRepository->create($input);
        return $this->sendSuccessResponse(route('carrierPayChannels.index'));

    }

    /**
     * Display the specified CarrierPayChannel.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $carrierPayChannel = $this->carrierPayChannelRepository->findWithoutFail($id);

        if (empty($carrierPayChannel)) {
            Flash::error('Carrier Pay Channel not found');

            return redirect(route('carrierPayChannels.index'));
        }

        return view('carrier_pay_channels.show')->with('carrierPayChannel', $carrierPayChannel);
    }

    /**
     * Show the form for editing the specified CarrierPayChannel.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $carrierPayChannel = $this->carrierPayChannelRepository->findWithoutFail($id);
        if (empty($carrierPayChannel)) {
            return $this->renderNotFoundPage();
        }
        $pay = \App\Models\Def\PayChannel::where('id', $carrierPayChannel['def_pay_channel_id'])->first();
        $parent = PayChannelType::where('id', $pay['pay_channel_type_id'])->first();
        $payc = PayChannelType::where('id', $parent['parent_id'])->first();
        $payChannelType = PayChannelType::where('parent_id', 0)->get();
        $parentPayChannelType = PayChannelType::where('parent_id', $parent['parent_id'])->get();
        $payChannelList = \App\Models\Def\PayChannel::where('pay_channel_type_id', $parent['id'])->get();

        $data['image_category'] = 5;
        $img = \App\Models\Image\CarrierImage::where($data)->get();
        return view('Carrier.carrier_pay_channels.edit')->with(['pay' => $pay, 'parent' => $parent, 'payc' => $payc, 'carrierPayChannel' => $carrierPayChannel, 'payChannelType' => $payChannelType, 'parentPayChannelType' => $parentPayChannelType, 'payChannelList' => $payChannelList, 'img' => $img]);
    }

    /**
     * Update the specified CarrierPayChannel in storage.
     *
     * @param  int $id
     * @param UpdateCarrierPayChannelRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCarrierPayChannelRequest $request)
    {
        $carrierPayChannel = $this->carrierPayChannelRepository->findWithoutFail($id);

        if (empty($carrierPayChannel)) {
            return $this->sendNotFoundResponse();
        }
        $input = $request->all();
        
        
        if (!empty($request->get('def_pay_channel_id'))) {
            $pay = \App\Models\Def\PayChannel::where('id', $request->get('def_pay_channel_id'))->first();
            $parent = PayChannelType::where('id', $pay['pay_channel_type_id'])->first();
            if ($parent['id'] == PayChannelType::SCAN_CODE_COMPANY_PAY) {
                if (!empty($request->get('qrcode'))) {
                    
                    $input['qrcode'] = $request->get('qrcode');
                } else {
                    return $this->sendErrorResponse('二维码不能为空');
                }
            } else {
                $input['qrcode'] = 0;
            }
        }
        $carrierPayChannel = $this->carrierPayChannelRepository->update($input, $id);
        return $this->sendSuccessResponse(route('carrierPayChannels.index'));
    }

    /**
     * Remove the specified CarrierPayChannel from storage.
     *删除
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id, Request $request)
    {
        $carrierPayChannel = $this->carrierPayChannelRepository->findWithoutFail($id);
        if (empty($carrierPayChannel)) {
            return $this->sendNotFoundResponse();
        }
        $this->carrierPayChannelRepository->delete($id);
        return $this->sendSuccessResponse(route('carrierPayChannels.index'));
    }

    /**
     * 支付方式二级联动数据
     */
    public function payment(Request $request)
    {
        $data['parent_id'] = $request->get('paytype');
        $classes = PayChannelType::where($data)->get();
//       $classes= PayChannelType::with('parentPayChannelList')->where($data)->get();
        echo json_encode($classes);
    }

    /**
     * 支付渠道二级联动数据
     */
    public function channelType(Request $request)
    {
        $data['pay_channel_type_id'] = $request->get('paychannel');
        $classes = \App\Models\Def\PayChannel::where($data)->get();
        echo json_encode($classes);
    }

    /**
     * 禁用启用
     * @param type $id
     * @param Request $request
     * @return type
     */
    public function saveStatus($id, Request $request)
    {
        $data['status'] = $request->get('status');
        $carrierPayChannel = $this->carrierPayChannelRepository->update($data, $id);
        if (empty($carrierPayChannel)) {
            return $this->sendNotFoundResponse();
        }
        return $this->sendSuccessResponse(route('carrierPayChannels.index'));
    }

    /**
     * 绑定三方支付
     * @param type $id
     * @return type
     */
    public function payList($id)
    {
        $data['carrier_id'] = \Auth::user()->carrier_id;
        $carrierPayChannel = $this->carrierPayChannelRepository->findWithoutFail($id);
        $data['def_pay_channel_id'] = $carrierPayChannel['def_pay_channel_id'];
        $paylist = \App\Models\Conf\CarrierThirdPartPay::where($data)->with("defPayChannel")->get();
        return view('Carrier.carrier_pay_channels.paylist')->with(['paylist' => $paylist, 'cid' => $id]);
    }

    public function bindPaylist($cid, Request $request)
    {
        $data['binded_third_part_pay_id'] = $request->get('binded_third_part_pay_id');

        $carrierPayChannel = $this->carrierPayChannelRepository->update($data, $cid);
        if (empty($carrierPayChannel)) {
            return $this->sendNotFoundResponse();
        }
        return $this->sendSuccessResponse(route('carrierPayChannels.index'));
    }

    /**
     * 解绑三方支付
     * @param type $id
     * @return type
     */
    public function unbundList($id)
    {

        $data['carrier_id'] = \Auth::user()->carrier_id;
        $paylist = \App\Models\Conf\CarrierThirdPartPay::where($data)->with("defPayChannel")->get();
        return view('Carrier.carrier_pay_channels.unbundlist')->with(['paylist' => $paylist, 'cid' => $id]);
    }

    public function unbundPaylist($cid, Request $request)
    {
        $data['binded_third_part_pay_id'] = null;
        $carrierPayChannel = $this->carrierPayChannelRepository->update($data, $cid);
        if (empty($carrierPayChannel)) {
            return $this->sendNotFoundResponse();
        }
        return $this->sendSuccessResponse(route('carrierPayChannels.index'));
    }


    public function showManualTransferRecordModal(){
        return view('Carrier.carrier_pay_channels.manual_transfer_record');
    }

    public function newManualTransferRecord(Request $request){
        $payChannels = implode(',',CarrierPayChannel::get(['id'])->map(function($element){return $element->id;})->toArray());
        $this->validate($request,[
            'transfer_type' => 'required|in:'.implode(',',array_keys(\App\Models\CarrierPayChannel::manualAdjustAmountTypeMeta())),
            'fee' => 'required|numeric|min:0',
            'transfer_out_bank' => 'different:transfer_in_bank|in:'.$payChannels,
            'transfer_in_bank'  => 'different:transfer_out_bank|in:'.$payChannels,
            'amount' => 'required|numeric|min:0',
//            'transfer_time' => 'required|date_format:Y-m-d H:i:s',
            'remark' => 'max:255'
        ]);
        if($request->get('amount') <= 0){
            return $this->sendErrorResponse('金额必须大于0元');
        }
        try{
            \DB::transaction(function () use ($request){
                $amount = $request->get('amount');
                if($transfer_out_bank = $request->get('transfer_out_bank')){
                    $payChannel = CarrierPayChannel::findOrFail($transfer_out_bank);
                    $payChannel->balance -= ($amount + $request->get('fee'));
                    if($payChannel->balance < 0){
                        throw new CarrierAccountException('出款银行卡余额不足');
                    }
                    $payChannel->update();
                    $log = new CarrierQuotaConsumptionLog();
                    $log->amount = -$request->get('amount');
                    $log->carrier_id = \WinwinAuth::carrierUser()->carrier_id;
                    $log->remark = $request->get('remark');
                    $log->related_pay_channel = $payChannel->id;
                    $log->consumption_source = '银行卡出款'.\App\Models\CarrierPayChannel::manualAdjustAmountTypeMeta()[$request->get('transfer_type')];
                    $log->pay_channel_remain_amount = $payChannel->balance;
                    $log->save();
                    if($request->get('fee')){
                        $log = new CarrierQuotaConsumptionLog();
                        $log->amount = -$request->get('fee');
                        $log->carrier_id = \WinwinAuth::carrierUser()->carrier_id;
                        $log->remark = $request->get('remark');
                        $log->related_pay_channel = $payChannel->id;
                        $log->consumption_source = \App\Models\CarrierPayChannel::manualAdjustAmountTypeMeta()[$request->get('transfer_type')].'手续费';
                        $log->pay_channel_remain_amount = $payChannel->balance;
                        $log->save();
                    }
                }
                if($transfer_in_bank = $request->get('transfer_in_bank')){
                    $payChannel = CarrierPayChannel::findOrFail($transfer_in_bank);
                    $payChannel->balance += $amount;
                    $payChannel->update();
                    $log = new CarrierQuotaConsumptionLog();
                    $log->amount = $request->get('amount');
                    $log->carrier_id = \WinwinAuth::carrierUser()->carrier_id;
                    $log->remark = $request->get('remark');
                    $log->related_pay_channel = $payChannel->id;
                    $log->consumption_source = '银行卡入款'.\App\Models\CarrierPayChannel::manualAdjustAmountTypeMeta()[$request->get('transfer_type')];
                    $log->pay_channel_remain_amount = $payChannel->balance;
                    $log->save();
                }
                if(!$transfer_out_bank && !$transfer_in_bank){
                    $payChannel = CarrierPayChannel::findOrFail($transfer_in_bank);
                    $payChannel->balance += $amount;
                    $log = new CarrierQuotaConsumptionLog();
                    $log->amount = -$request->get('amount');
                    $log->carrier_id = \WinwinAuth::carrierUser()->carrier_id;
                    $log->remark = $request->get('remark');
                    $log->consumption_source = \App\Models\CarrierPayChannel::manualAdjustAmountTypeMeta()[$request->get('transfer_type')];
                    $log->save();
                }
            });
            return $this->sendSuccessResponse();
        }catch (\Exception $e){
            return $this->sendErrorResponse($e->getMessage());
        }

    }

}
