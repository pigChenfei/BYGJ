<?php
/**
 * Created by PhpStorm.
 * User: wugang
 * Date: 17/3/6
 * Time: 下午12:59
 */

namespace App\Http\Controllers\Carrier;


use App\Http\Controllers\AppBaseController;
use App\Http\Requests\Carrier\UpdateCarrierPlayerLevelBankCardMapRequest;
use App\Models\Map\CarrierPlayerLevelBankCardMap;

class CarrierPlayerLevelBankCardMapController extends AppBaseController
{


    public function update(UpdateCarrierPlayerLevelBankCardMapRequest $request){

        try{
            \DB::transaction(function() use ($request){
                $player_id = $request->get('player_level_id');
                CarrierPlayerLevelBankCardMap::where('carrier_player_level_id',$player_id)->delete();
                $bankIds = $request->get('selected_bank');
                if($bankIds){
                    foreach($bankIds as $id){
                        $map = new CarrierPlayerLevelBankCardMap();
                        $map->carrier_pay_channle_id = $id;
                        $map->carrier_player_level_id = $player_id;
                        $map->save();
                    }
                }
            });
            return $this->sendSuccessResponse($request);
        }catch(\Exception $e){
            return $this->sendErrorResponse($e->getMessage());
        }
    }

}