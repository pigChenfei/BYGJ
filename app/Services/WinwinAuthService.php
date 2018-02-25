<?php
/**
 * Created by PhpStorm.
 * User: wugang
 * Date: 17/3/9
 * Time: 上午11:40
 */

namespace App\Services;


use App\Entities\CacheConstantPrefixDefine;
use App\Models\Carrier;
use App\Models\Player;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class WinwinAuthService
{

    /**
     * @return \App\Models\CarrierUser
     */
    public function carrierUser(){
        return \Auth::user();
    }

    /**
     * @return \App\Models\CarrierAgentUser
     */
    public function agentUser(){
        return $this->agentAuth()->user();
    }

    /**
     * @return \Auth
     */
    public function agentAuth(){
        return \Auth::guard('agent');
    }
    /**
     * @return \App\Models\AdminUser
     */
    public function adminUser(){
        return $this->adminAuth()->user();
    }

    /**
     * @return \Auth
     */
    public function adminAuth(){
        return \Auth::guard('admin');
    }

    /**
     * @return \App\Models\Player
     */
    public function memberUser(){
        $user = $this->memberAuth()->user();
        if($user){
            \Cache::put(CacheConstantPrefixDefine::MEMBER_USER_ONLINE_REMEMBER_CACHE_PREFIX.$user->player_id,1,\Config::get('session.lifetime'));
        }
        return $user;
    }


    /**
     * @return \Auth
     */
    public function memberAuth(){
        return \Auth::guard('member');
    }


    /**
     * 当前前端网站所属运营商
     * @return \App\Models\Carrier
     */
    public function currentWebCarrier(){
        try{
            return \App::make('currentWebCarrier');
        }catch(\Exception $e){
            throw new NotFoundHttpException;
        }
    }

    /**
     * 当前前端网站所属代理商
     * @return \App\Models\CarrierAgentDomain
     */
    public function currentWebAgent(){
        try{
            return \App::make('currentWebAgent');
        }catch(\Exception $e){
            throw new NotFoundHttpException;
        }
    }

    /**
     * 判断当前是否是总后台系统
     * @return bool
     */
    public function isCurrentAdminSystem(){
        try{
            $role = \App::make('currentRole');
            return $role == 'admin';
        }catch(\Exception $e){
            return false;
        }
    }

    /**
     * 判断当前是否是运营商后台系统
     * @return bool
     */
    public function isCurrentCarrierAdminSystem(){
        try{
            $role = \App::make('currentRole');
            return $role == 'carrierAdmin';
        }catch(\Exception $e){
            return false;
        }
    }


    /**
     * 判断当前是否是玩家前端网站
     * @return bool
     */
    public function isCurrentMemberFrontEndSystem(){
        try{
            $role = \App::make('currentRole');
            return $role == 'web';
        }catch(\Exception $e){
            return false;
        }
    }

    /**
     * 判断当前是否是玩家后台
     * @return bool
     */
    public function isCurrentMemberAdminSystem(){
        try{
            $role = \App::make('currentRole');
            return $role == 'memberAdmin';
        }catch(\Exception $e){
            return false;
        }
    }

    /**
     * 判断当前是否是代理商后台系统
     * @return bool
     */
    public function isCurrentAgentAdminSystem(){
        try{
            $role = \App::make('currentRole');
            return $role == 'agent';
        }catch(\Exception $e){
            return false;
        }
    }


}