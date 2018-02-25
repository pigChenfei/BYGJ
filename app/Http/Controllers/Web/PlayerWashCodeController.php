<?php
namespace App\Http\Controllers\Web;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\Log\PlayerRebateFinancialFlowNew;

/**
 * Created by PhpStorm.
 * User: winwin
 * Date: 2017/3/16
 * Time: 下午10:09
 */
class PlayerWashCodeController extends AppBaseController
{

    public function washCodeRecords(Request $request){
        $type = $request->get('type', '');
        $status = $request->get('status');
        $perPage = $request->get('perPage', 10);
        
        $t =$request->get('t','');
        $start_time ='';
        $end_time   ='';

        $time=time();
        if(!empty($t))
        {
            if($t==1)
            {
                $start_time =date("Y-m-d",$time).' 00:00:00';
            }
            else if($t==2)
            {
                $start_time = date('Y-m-d H:i:s',strtotime('-1 sunday', time()));
            }
            else if($t==3)
            {
                $start_time = date('Y-m-d H:i:s',strtotime(date('Y-m', time()).'-01 00:00:00'));
            }
            $end_time   =date("Y-m-d",$time).' 23:59:59';
        }

        $start_time = empty($start_time)?$request->get('start_time', ''):$start_time;
        $end_time = empty($end_time)?$request->get('end_time', ''):$end_time;

        $parameter = array(
            'status'=>$status,
            'start_time'=>$start_time,
            'end_time'=>$end_time,
        );

        $rebateFinancialStatus = PlayerRebateFinancialFlowNew::statusMeta();

        $playerRebateFinancialFlow = PlayerRebateFinancialFlowNew::where('player_id', \WinwinAuth::memberUser()->player_id)
            ->where('is_effect', 0);
        $playerRebateFinancialFlow = $playerRebateFinancialFlow->where('rebate_type', 2)->orWhere('settled_type', 1)->with('gamePlat');

        if (isset($status) && $status !=''){
            $playerRebateFinancialFlow = $playerRebateFinancialFlow->where('is_already_settled', $status);
        }
        if ($start_time){
            $playerRebateFinancialFlow = $playerRebateFinancialFlow->whereDate('created_at', '>=', $start_time);
        }
        if ($end_time){
            $playerRebateFinancialFlow = $playerRebateFinancialFlow->whereDate('created_at', '<=', $end_time);
        }
        $playerRebateFinancialFlow = $playerRebateFinancialFlow ->orderBy('created_at', 'DESC')->paginate($perPage);

        if($request->ajax()){
            if($type){
                return \WTemplate::washCodeLists()->with('playerRebateFinancialFlow', $playerRebateFinancialFlow);
            }
            return \WTemplate::washCodeRecords()->with(['playerRebateFinancialFlow'=> $playerRebateFinancialFlow, 'rebateFinancialStatus'=>$rebateFinancialStatus]);
        }
        if($this->isMobile())
        {
            $str='';
            foreach ($playerRebateFinancialFlow as $item) 
            {
                $str.="{'洗码编号':'".$item->id."','平台':'".$item->gamePlat->game_plat_name."','有效投注额':'".$item->bet_flow_amount."','洗码金额':'".$item->rebate_financial_flow_amount."','派发时间':'".$item->settled_at."','状态':'".$item->statusMeta()[$item->is_already_settled]."'}";
            }
            $str=rtrim($str,',');
            return \WTemplate::washCodeRecords('m')->with(['str'=>$str,'playerRebateFinancialFlow'=> $playerRebateFinancialFlow, 'rebateFinancialStatus'=>$rebateFinancialStatus,'parameter'=>$parameter]);
        }
        else
        {
            return \WTemplate::washCodeRecords()->with(['playerRebateFinancialFlow'=> $playerRebateFinancialFlow, 'rebateFinancialStatus'=>$rebateFinancialStatus,'parameter'=>$parameter]);
        }
        
    }
}