<?php

namespace App\Http\Middleware;

use App\Helpers\IP\RealIpHelper;
use App\Models\CarrierUser;
use App\Models\Log\CarrierDashOperateLog;
use App\Models\RolesModel\Permission;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class CarrierOperationLog
{


    /**
     * @var Response
     */
    private $response;


    protected $except = [
        'login' => ['get'],
        'logout' => ['get'],
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->response = $next($request);
        //超级管理员不记录操作数据
        if (\WinwinAuth::carrierUser() && \WinwinAuth::carrierUser()->is_super_admin){
            return $this->response;
        }
        \DB::enableQueryLog();

        $data = new \stdClass();

        $data->ip = RealIpHelper::getIp();
        $data->data = array_except($request->all(),['_token']);
        if (empty($data->data)){
            $data->data = null;
        }
        $data->data = json_encode($data->data);

        if($this->response instanceof RedirectResponse){
            //注意点:运算符优先级
            if(($session = $this->response->getSession()) && $errors = $session->get('errors')){
                    $data->remark = implode('|',$errors->all());
            }
        }
        if ($this->response instanceof JsonResponse){
           $responseData = $this->response->getData(true);
            if(isset($responseData['success']) && isset($responseData['message']) && $responseData['success'] == false){
                $data->remark = $responseData['message'];
            }
        }

        foreach ($this->except as $except => $methods) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }
            if(!$methods && $request->is($except)){
                return $this->response;
            }
            foreach ($methods as $method){
                if(strtoupper($method) == $request->getMethod() && $request->is($except)){
                    return $this->response;
                }
            }
        }
        $logs = \DB::getQueryLog();
        if($logs){
            //过滤select查询 只记录有修改的操作
            $filterLogs = array_filter($logs,function($element){
                return strpos($element['query'],'update') === 0 || strpos($element['query'],'delete') === 0 || strpos($element['query'],'replace') === 0 || strpos($element['query'],'drop') === 0 ||
                strpos($element['query'],'truncated') === 0 || strpos($element['query'],'insert') === 0;
            });
            if($filterLogs){
                $this->saveLoginLog($data,$request);
            }
        }
        return $this->response;
    }


    protected function saveLoginLog($data,$request){
        $carrierUser = \WinwinAuth::carrierUser();
        if(!$carrierUser){
            $carrierUser = CarrierUser::where('username', $request->get('username'))->with('dashLoginConf')->first();
        }
        if (!$carrierUser) {
            return;
        }
        $data->status_code = $this->response->status();
        $route = Permission::where('name',\Route::current()->getActionName())->first();
        if(!$route){
            return;
        }
        $data->route_id = $route->id;
        $data->user_id = $carrierUser->id;
        $data->carrier_id = $carrierUser->carrier_id;
        $data->created_at = Carbon::now();
        $data->updated_at = Carbon::now();
        $data = get_object_vars($data);
        CarrierDashOperateLog::create($data);
    }


}
