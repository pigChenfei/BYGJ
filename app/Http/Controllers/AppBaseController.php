<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use InfyOm\Generator\Utils\ResponseUtil;
use Response;

/**
 * @SWG\Swagger(
 *   basePath="/api/v1",
 *   @SWG\Info(
 *     title="Laravel Generator APIs",
 *     version="1.0.0",
 *   )
 * )
 * This class should be parent class for other API controllers
 * Class AppBaseController
 */
class AppBaseController extends Controller
{
    /**
     * @param $result
     * @param $message
     * @return \Illuminate\Http\JsonResponse
     */
    public static function sendResponse($result, $message = 'ok')
    {
        return Response::json(ResponseUtil::makeResponse($message, $result));
    }

    /**
     * @param string|array $error
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public static function sendError($error, $code = 404)
    {
        if(is_array($error)){
            return Response::json(array_merge($error,['success' => false]), $code);
        }
        return Response::json(ResponseUtil::makeError($error), $code);
    }

    /**
     *响应404请求
     * @return Response
     */
    public function sendNotFoundResponse(){
        if(Request::capture()->ajax()){
            return self::sendError('找不到资源',404);
        }
        return $this->renderNotFoundPage();
    }

    /**
     * 渲染错误响应
     * @param string $error_msg
     * @return mixed
     */
    public function sendErrorResponse($error_msg = '系统错误',$statusCode = 403){
        if(Request::capture()->ajax()){
            return self::sendError($error_msg,$statusCode);
        }
        return view('Carrier.errors.500')->with('error',$error_msg);
    }

    /**
     * 发送成功的响应,后台使用
     * @param null $redirect_route
     * @return mixed
     */
    public function sendSuccessResponse($redirect_route = null){
        if(Request::capture()->ajax()){
            return self::sendResponse([],'ok');
        }
        if($redirect_route){
            return redirect($redirect_route);
        }
        redirect(route('/'));
    }


    /**
     * 渲染前端页面,如果是ajax请求,返回json格式数据,否则渲染视图,前台使用
     * @param View $view
     * @param $data
     * @return mixed
     */
    public function renderSuccessResponse(View $view, $data){
        if(Request::capture()->ajax()){
            return self::sendResponse($data,'ok');
        }
        return $view->with('data' , $data);
    }

    /**
     * 渲染404页面
     * @return View
     */
    public function renderNotFoundPage(){
        return view('Carrier.errors.404');
    }

    public function isMobile()
    {
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset ($_SERVER['HTTP_X_WAP_PROFILE'])) {
            return TRUE;
        }
        // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        if (isset ($_SERVER['HTTP_VIA'])) {
            return stristr($_SERVER['HTTP_VIA'], "wap") ? TRUE : FALSE;// 找不到为flase,否则为TRUE
        }
        // 判断手机发送的客户端标志,兼容性有待提高
        if (isset ($_SERVER['HTTP_USER_AGENT'])) {
            $clientkeywords = array(
                'mobile',
                'nokia',
                'sony',
                'ericsson',
                'mot',
                'samsung',
                'htc',
                'sgh',
                'lg',
                'sharp',
                'sie-',
                'philips',
                'panasonic',
                'alcatel',
                'lenovo',
                'iphone',
                'ipod',
                'blackberry',
                'meizu',
                'android',
                'netfront',
                'symbian',
                'ucweb',
                'windowsce',
                'palm',
                'operamini',
                'operamobi',
                'openwave',
                'nexusone',
                'cldc',
                'midp',
                'wap'
            );
            // 从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
                return TRUE;
            }
        }
        if (isset ($_SERVER['HTTP_ACCEPT'])) { // 协议法，因为有可能不准确，放到最后判断
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== FALSE) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === FALSE || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
                return TRUE;
            }
        }
        return FALSE;
    }

}
