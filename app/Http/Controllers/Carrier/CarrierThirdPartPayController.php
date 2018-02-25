<?php
namespace App\Http\Controllers\Carrier;

use App\DataTables\Carrier\CarrierThirdPartPayDataTable;
use App\Http\Requests\Carrier;
use App\Http\Requests\Carrier\CreateCarrierThirdPartPayRequest;
use App\Http\Requests\Carrier\UpdateCarrierThirdPartPayRequest;
use App\Models\Def\PayChannel;
use App\Repositories\Carrier\CarrierThirdPartPayRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Response;
use App\Models\Def\PayChannelType;

class CarrierThirdPartPayController extends AppBaseController
{

    /** @var  CarrierThirdPartPayRepository */
    private $carrierThirdPartPayRepository;

    public function __construct(CarrierThirdPartPayRepository $carrierThirdPartPayRepo)
    {
        $this->carrierThirdPartPayRepository = $carrierThirdPartPayRepo;
    }

    /**
     * Display a listing of the CarrierThirdPartPay.
     *
     * @param CarrierThirdPartPayDataTable $carrierThirdPartPayDataTable
     * @return Response
     */
    public function index(CarrierThirdPartPayDataTable $carrierThirdPartPayDataTable)
    {
        return $carrierThirdPartPayDataTable->render('Carrier.carrier_third_part_pays.index');
    }

    /**
     * Show the form for creating a new CarrierThirdPartPay.
     * isThirdPartPay
     *
     * @return Response
     */
    public function create()
    {
        $ids = array();
        $data['parent_id'] = 0;
        $data['id'] = PayChannelType::THIRD_PART_PAY;
        $payChannelType = PayChannelType::where($data)->first();
        $paySub = PayChannelType::where([
            'parent_id' => $payChannelType['id']
        ])->get();
        foreach ($paySub as $key => $value) {
            $ids[] = $value['id'];
        }
        $payChannelList = \App\Models\Def\PayChannel::with('payChannelType')->whereIn('pay_channel_type_id', $ids)->get();
        return view('Carrier.carrier_third_part_pays.create')->with('payChannelList', $payChannelList);
    }

    /**
     * Store a newly created CarrierThirdPartPay in storage.
     *
     * @param CreateCarrierThirdPartPayRequest $request
     *
     * @return Response
     */
    public function store(CreateCarrierThirdPartPayRequest $request)
    {
        $input = $request->all();
        $input['carrier_id'] = \Auth::user()->carrier_id;
        
        if (! empty($request->get('pay_ids_json'))) {
            $pay_ids_json = explode(',', $request->get('pay_ids_json'));
            foreach ($pay_ids_json as $key => $value) {
                $pay_ids_jsons[] = "" . $value . "";
            }
            $input['pay_ids_json'] = json_encode($pay_ids_jsons, true);
        } else {
            $input['pay_ids_json'] = null;
        }
        $this->carrierThirdPartPayRepository->create($input);
        return $this->sendSuccessResponse(route('carrierThirdPartPays.index'));
    }

    /**
     * Display the specified CarrierThirdPartPay.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $carrierThirdPartPay = $this->carrierThirdPartPayRepository->findWithoutFail($id);
        
        if (empty($carrierThirdPartPay)) {
            Flash::error('Carrier Third Part Pay not found');
            
            return redirect(route('carrierThirdPartPays.index'));
        }
        
        return view('carrier_third_part_pays.show')->with('carrierThirdPartPay', $carrierThirdPartPay);
    }

    /**
     * Show the form for editing the specified CarrierThirdPartPay.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $carrierThirdPartPay = $this->carrierThirdPartPayRepository->findWithoutFail($id);
        if (empty($carrierThirdPartPay)) {
            return $this->renderNotFoundPage();
        }
        $data['parent_id'] = 0;
        $data['id'] = PayChannelType::THIRD_PART_PAY;
        $payChannelType = PayChannelType::where($data)->first();
        $paySub = PayChannelType::where([
            'parent_id' => $payChannelType['id']
        ])->get();
        foreach ($paySub as $key => $value) {
            $ids[] = $value['id'];
        }
        $payChannelList = \App\Models\Def\PayChannel::with('payChannelType')->whereIn('pay_channel_type_id', $ids)->get();
        return view('Carrier.carrier_third_part_pays.edit')->with([
            'carrierThirdPartPay' => $carrierThirdPartPay,
            'payChannelList' => $payChannelList
        ]);
    }

    /**
     * Update the specified CarrierThirdPartPay in storage.
     *
     * @param int $id
     * @param UpdateCarrierThirdPartPayRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCarrierThirdPartPayRequest $request)
    {
        $carrierThirdPartPay = $this->carrierThirdPartPayRepository->findWithoutFail($id);
        if (empty($carrierThirdPartPay)) {
            return $this->sendNotFoundResponse();
        }
        $input = $request->all();
        $input['private_key'] = $request->get('private_key');
        if (empty($input['private_key'])) {
            $input['private_key'] = $carrierThirdPartPay['private_key'];
        }
        
        if (! empty($request->get('pay_ids_json'))) {
            $pay_ids_json = explode(',', $request->get('pay_ids_json'));
            foreach ($pay_ids_json as $key => $value) {
                $pay_ids_jsons[] = "" . $value . "";
            }
            $input['pay_ids_json'] = json_encode($pay_ids_jsons, true);
        } else {
            $input['pay_ids_json'] = null;
        }
        
        $carrierThirdPartPay = $this->carrierThirdPartPayRepository->update($input, $id);
        return $this->sendSuccessResponse(route('carrierThirdPartPays.index'));
    }

    /**
     * Remove the specified CarrierThirdPartPay from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id, Request $request)
    {
        $carrierThirdPartPay = $this->carrierThirdPartPayRepository->findWithoutFail($id);
        
        if (empty($carrierThirdPartPay)) {
            Flash::error('Carrier Third Part Pay not found');
            
            return redirect(route('carrierThirdPartPays.index'));
        }
        
        $this->carrierThirdPartPayRepository->delete($id);
        if ($request->ajax()) {
            return self::sendResponse([], 'success');
        }
        Flash::success('操作成功.');
        
        return redirect(route('carrierThirdPartPays.index'));
    }

    /**
     * Remove the specified CarrierThirdPartPay from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function getInfo(Request $request)
    {
        $payChannel = PayChannel::find($request->input('id'));
        
        if (empty($payChannel)) {
            
            Flash::error('支付平台不存在');
            
            return $this->sendError('支付平台不存在,请重试');
        }
        
        return $this->sendResponse($payChannel);
    }
}
