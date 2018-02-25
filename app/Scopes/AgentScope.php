<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
/**
 * Created by PhpStorm.
 * User: winwin
 * Date: 2017/3/15
 * Time: 下午2:24
 */
class AgentScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        //判断当前web环境是否是代理商后台
        if(\WinwinAuth::agentUser() && \WinwinAuth::isCurrentAgentAdminSystem()){
            return $builder->where('agent_id',\WinwinAuth::agentUser()->id);
        }
        return $builder;
    }

}