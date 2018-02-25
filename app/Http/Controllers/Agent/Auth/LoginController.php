<?php

namespace App\Http\Controllers\Agent\Auth;
use App\Exceptions\AgentAccountException;
use App\Http\Controllers\AppBaseController;
use App\Models\CarrierAgentUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use InfyOm\Generator\Utils\ResponseUtil;
use Response;
class LoginController extends AppBaseController
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

//    protected function validator(array $data)
//    {
//        return Validator::make($data, [
//            'name' => 'required|max:255',
//            'password' => 'required|min:6',
//        ]);
//    }
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
      protected $redirectTo;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:agent', ['except' => 'logout']);
        $this->redirectTo = \App::environment() == 'production' ? '/' : '/agent/admin';
    }

    public function showLoginForm(){
        return view('Agent.auth.login');
    }

    public function username()
    {
        return 'username';
    }
    
     public function login(Request $request)
     {
         //验证账号密码输入不能为空
         $this->validateLogin($request);
         //查询输入账号是否存在
         $agentUser = CarrierAgentUser::where('username', $request->get('username'))->with('agentLoginConf')->first();
         if ($agentUser){
             $number = $agentUser->agentLoginConf->agent_login_failed_count_when_locked;
             $reason = $agentUser->agentLoginConf->agent_forbidden_login_comment;
             //判断是否多次登录
             if ($this->hasTooManyLoginAttempts($request,$number)) {
                 //调用了一个Lockout事件
                 $this->fireLockoutEvent($request);
                 $this->lockAccount($agentUser);
                 //返回错误提示信息
                 return $this->sendLockoutResponse($request,$reason);
             }
             //自增登录失败$key次数
             $this->incrementLoginAttempts($request);
            try{
                \WinwinAuth::agentAuth()->loginUsingId($agentUser->id);
                return redirect($this->redirectTo)->with('success', '登录成功！');
//                    if ($agentUser->isActive()){
//                        if (\Hash::check($request->get('password'), $agentUser->password) == true){
//                            \WinwinAuth::agentAuth()->loginUsingId($agentUser->id);
//                            return redirect($this->redirectTo)->with('success', '登录成功！');
//                        }else{
//                            return $this->sendFailedResponse($request,['password'=>'密码输入错误']);
//                        }
//                    }
            }catch (AgentAccountException $e){
                return $this->sendFailedResponse($request,['username'=>$e->getMessage()]);
            }
         }
         return $this->sendFailedLoginResponse($request);
     }
     
//    public static function sendError($error, $code = 404)
//    {
//        return Response::json(ResponseUtil::makeError($error), $code);
//    }

    protected function sendFailedResponse(Request $request,$array){
        return redirect()->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors(
                $array
            );
    }

    protected function hasTooManyLoginAttempts(Request $request,$number)
    {
        // 调用RateLimiter对象的tooManyAttempts()方法，并传值给它
        return $this->limiter()->tooManyAttempts(
        // 参数意思： 给缓存的key, 指定最多出错登录次数，几分钟失效43200
            $this->throttleKey($request), $number, 1
        );

    }

    protected function sendLockoutResponse(Request $request,$reason)
    {
        // 调用RateLimiter对象的availableIn方法
        // 并将类是：zhoujiping@zhoujiping.com|10.10.0.1  的key作为参数传入
        // 返回的是限制登录的剩余时间（多少秒）
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );

        $message = Lang::get($reason, ['seconds' => $seconds]);
        return redirect()->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors([$this->username() => $message]);
    }

    //密码错误超过次数锁定当前账号
    protected function lockAccount($agentUser){
        $agentUser->status = 0;
        $agentUser->save();
    }

    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            $this->credentials($request), $request->has('remember')
        );
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->flush();
        $request->session()->regenerate();
        return redirect($this->redirectTo);
    }
//    protected function guard()
//    {
//        return \WinwinAuth::agentAuth();
//    }
}
