<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use App\Repositories\Admin\PayTypeRepository;
use App\DataTables\Admin\PayTypeDataTable;
use App\Http\Requests\Admin\CreatePayTypeRequest;
use App\Models\Def\PayChannel;
use App\Models\Def\PayChannelType;
use Flash;
use Illuminate\Http\Request;

class PayTypeController extends AppBaseController
{

    public $paymentRepos;

    public function __construct(PayTypeRepository $paymentRepos)
    {
        $this->paymentRepos = $paymentRepos;
    }

    public function index(PayTypeDataTable $paymentDatatable)
    {
        return $paymentDatatable->render('Admin.payType.index');
    }

    public function create()
    {
        $channelType = PayChannelType::with('childInfo')->where('parent_id', 0)->get();
        return view('Admin.payType.create', compact('channelType'));
    }

    public function store(CreatePayTypeRequest $request)
    {
        $input = $request->all();
        if ($request->get('id')) {
            return $this->update($request->get('id'), $input);
        }
        try {
            $channel = PayChannelType::where([
                'type_name' => $input['type_name'],
                'parent_id' => $input['parent_id']
            ])->first();
            if ($channel) {
                Flash::error('支付类型已存在');
                
                return redirect()->back();
            }
            $payChannel = new PayChannelType();
            $payChannel->fill($input);
            $payChannel->save();
        } catch (\Exception $e) {
            Flash::error('支付类型添加失败');
            
            return redirect()->back();
        }
        
        Flash::success('支付类型添加成功');
        
        return redirect(route('payTypes.index'));
    }

    public function edit($id)
    {
        $payChannel = $this->paymentRepos->findWithoutFail($id);
        if (empty($payChannel)) {
            return $this->sendNotFoundResponse();
        }
        $channelType = PayChannelType::with('childInfo')->where('parent_id', 0)->get();
        return view('Admin.payType.edit', compact('payChannel', 'channelType'));
    }

    public function update(CreatePayTypeRequest $request, $id)
    {
        $input = $request->all();
        $payChannel = $this->paymentRepos->findWithoutFail($id);
        
        if ($id == $input['parent_id']) {
            Flash::error('父类不能与自身相同');

            return redirect()->back();
        }
        if (empty($payChannel)) {
            return $this->sendNotFoundResponse();
        }
        try {
            $this->paymentRepos->update($input, $id);
        } catch (\Exception $e) {
            Flash::error('支付类型修改失败');
            
            return redirect()->back();
        }
        Flash::success('支付类型修改成功');
        
        return redirect(route('payTypes.index'));
    }

    public function destroy($id,Request $request)
    {
        $carrierThirdPartPay = $this->paymentRepos->findWithoutFail($id);

        if (empty($carrierThirdPartPay)) {
            Flash::error('资源不存在');

            return redirect(route('payTypes.index'));
        }

        $this->carrierThirdPartPayRepository->delete($id);
        if($request->ajax()){
            return self::sendResponse([],'success');
        }
        Flash::success('操作成功.');

        return redirect(route('payTypes.index'));
    }
}

