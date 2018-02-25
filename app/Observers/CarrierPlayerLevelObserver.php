<?php
namespace App\Observers;

use App\Models\CarrierPlayerGamePlatRebateFinancialFlow;
use \App\Models\CarrierPlayerLevel as CPL;
use App\Models\Map\CarrierGamePlat;

class CarrierPlayerLevelObserver
{

    public function created(CPL $cpl)
    {
        $gamePlate = CarrierGamePlat::select('game_plat_id')->where('carrier_id', $cpl->carrier_id)->get();

        \DB::beginTransaction();
        try{
            foreach ($gamePlate as $v){
                $info = new CarrierPlayerGamePlatRebateFinancialFlow();
                $info->carrier_id = $cpl->carrier_id;
                $info->carrier_player_level_id = $cpl->id;
                $info->carrier_game_plat_id = $v->game_plat_id;
                $info->save();
            }
            \DB::commit();
        }catch (\Exception $e){
            \DB::rollBack();
            \Log::error([
                'CarrierPlayerLevelObserver' => $e->getMessage()
            ]);
        }
    }
}

