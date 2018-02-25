<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\Admin\CreatePlatChildRequest;
use App\Models\Def\Game;
use App\Models\Def\GamePlat;
use App\Models\Def\MainGamePlat;
use App\Models\Map\CarrierGame;
use App\Models\Map\CarrierGamePlat;
use App\Repositories\Admin\PlatRepository;
use App\Http\Requests\Admin\CreatePlatRequest;
use Flash;
use Illuminate\Http\Request;

class PlatController extends AppBaseController
{

    public $paymentRepos;

    public function __construct(PlatRepository $paymentRepos)
    {
        $this->paymentRepos = $paymentRepos;
    }

    public function index()
    {
        $plats = MainGamePlat::with('gamePlats')->orderBy('created_at', 'desc')->get();
        return view('Admin.plat.index', compact('plats'));
    }

    public function create()
    {
        return view('Admin.plat.create');
    }
    public function createChild($id)
    {
        $payChannel = $this->paymentRepos->findWithoutFail($id);
        return view('Admin.plat.createChild',compact('payChannel'));
    }

    public function store(CreatePlatRequest $request)
    {
        $input = $request->all();
        if ($request->get('id')) {
            return $this->update($request, $request->get('id'));
        }
        try {
            $channel = MainGamePlat::where([
                'main_game_plat_code' => $input['main_game_plat_code']
            ])->first();
            if ($channel) {
                Flash::error('主游戏平台已存在');
                
                return redirect()->back();
            }
            $payChannel = new MainGamePlat();
            $payChannel->fill($input);
            $payChannel->save();
        } catch (\Exception $e) {
            Flash::error('主游戏平台添加失败');
            
            return redirect()->back();
        }
        
        Flash::success('主游戏平台添加成功');
        
        return redirect(route('plats.index'));
    }
    public function storeChild(CreatePlatChildRequest $request)
    {
        $input = $request->all();
        if ($request->get('id')) {
            return $this->update($request, $request->get('id'));
        }
        try {
            $channel = GamePlat::where([
                'game_plat_name' => $input['game_plat_name']
            ])->first();
            if ($channel) {
                Flash::error('游戏平台已存在');

                return redirect()->back();
            }
            $payChannel = new GamePlat();
            $payChannel->fill($input);
            $payChannel->save();
        } catch (\Exception $e) {
            Flash::error('游戏平台添加失败');

            return redirect()->back();
        }

        Flash::success('游戏平台添加成功');

        return redirect(route('plats.index'));
    }

    public function edit($id)
    {
        $payChannel = MainGamePlat::find($id);
        if (empty($payChannel)) {
            Flash::error('参数错误');

            return redirect()->back();
        }
        return view('Admin.plat.edit', compact('payChannel'));
    }
    public function editChild($id)
    {
        $payChannel = GamePlat::with('mainGamePlat')->find($id);
        if (empty($payChannel)) {
            Flash::error('参数错误');

            return redirect()->back();
        }
        $channelType = MainGamePlat::all();
        return view('Admin.plat.editChild', compact('payChannel','channelType'));
    }

    public function update(CreatePlatRequest $request, $id)
    {
        $input = $request->all();
        $payChannel = $this->paymentRepos->findWithoutFail($id);
        

        if (empty($payChannel)) {
            Flash::error('主游戏平台不存在');

            return redirect()->back();
        }
        try {
            $this->paymentRepos->update($input, $id);
        } catch (\Exception $e) {
            Flash::error('主游戏平台失败');
            
            return redirect()->back();
        }
        Flash::success('主游戏平台修改成功');
        
        return redirect(route('plats.index'));
    }
    public function updateChild(CreatePlatChildRequest $request, $id)
    {
        $input = $request->all();
        $payChannel = GamePlat::find($id);


        if (empty($payChannel)) {
            Flash::error('游戏平台不存在');

            return redirect()->back();
        }
        try {
            $payChannel->update($input);
        } catch (\Exception $e) {
            Flash::error('游戏平台失败');

            return redirect()->back();
        }
        Flash::success('游戏平台修改成功');

        return redirect(route('plats.index'));
    }

    public function destroy($id,Request $request)
    {
        $carrierThirdPartPay = $this->paymentRepos->findWithoutFail($id);

        if (empty($carrierThirdPartPay)) {
            return $this->sendErrorResponse('资源不存在');
        }
        if (!empty($carrierThirdPartPay->gamePlats())) {

            return $this->sendErrorResponse('其下有子平台，不能直接删除');
        }

        $this->paymentRepos->delete($id);
        if($request->ajax()){
            return self::sendResponse([],'success');
        }
        Flash::success('操作成功.');

        return redirect(route('plats.index'));
    }

    public function destroyChild($id,Request $request)
    {
        $carrierThirdPartPay = GamePlat::find($id);

        if (empty($carrierThirdPartPay)) {
            return $this->sendErrorResponse('资源不存在');
        }
        try{
            $games = Game::where('game_plat_id', $id)->pluck('game_id');

            CarrierGamePlat::where('game_plat_id',$id)->delete();

            CarrierGame::whereIn('game_id',$games)->delete();

            $carrierThirdPartPay->delete();
        }catch (\Exception $e){
            return $this->sendErrorResponse('删除失败，请重试');
        }
        if($request->ajax()){
            return self::sendResponse([],'success');
        }
        Flash::success('操作成功.');

        return redirect(route('plats.index'));
    }
}

