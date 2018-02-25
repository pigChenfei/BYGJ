<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class GameServiceFacade extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'GameService';
    }
}

