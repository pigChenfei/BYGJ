<?php
namespace App\Vendor\Game\Imp;

class GameBase
{

    protected $module;

    public function __construct(GameImp $game)
    {
        $this->module = $game;
    }

    public static function __callStatic($method, $args)
    {
        return $this->module->$method(...$args);
    }

    public function __call($method, $args)
    {
        return $this->module->$method(...$args);
    }
}

