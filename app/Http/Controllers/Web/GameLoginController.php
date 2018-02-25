<?php
namespace App\Http\Controllers\Web;
use App\Http\Controllers\AppBaseController;
/**
 * Created by PhpStorm.
 * User: winwin
 * Date: 2017/3/16
 * Time: 下午10:09
 */
class GameLoginController extends AppBaseController
{



        public function gameLoginPage(){

            $player = \WinwinAuth::memberUser();

        }

}