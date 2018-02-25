<?php
namespace App\Vendor\Game\Imp;

use App\Models\PlayerTransfer;
use App\Models\PlayerGameAccount;

abstract class GameAbstract implements GameImp
{

    public function checkTransfer(PlayerTransfer $playTransfer, PlayerGameAccount $account)
    {
        return 0;
    }
}

