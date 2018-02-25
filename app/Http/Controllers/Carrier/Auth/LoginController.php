<?php
namespace App\Http\Controllers\Carrier\Auth;

use App\Http\Controllers\AppBaseController;
use App\Models\CarrierUser;
use App\Models\Conf\CarrierDashLoginConf;
use App\Models\Log\CarrierDashOperateLog;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use App\Http\Requests\Carrier\UpdateCarrierUserRequest;
use App\Models\RolesModel\Permission;

// use Illuminate\Foundation\Auth\ThrottlesLogins;
class LoginController extends AppBaseController
{
    /*
     * |--------------------------------------------------------------------------
     * | Login Controller
     * |--------------------------------------------------------------------------
     * |
     * | This controller handles authenticating users for the application and
     * | redirecting them to your home screen. The controller uses a trait
     * | to conveniently provide its functionality to your applications.
     * |
     */
    
    use AuthenticatesUsers;

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
        $this->middleware('guest', [
            'except' => ['logout','showLoginForm']
        ]);
        $this->redirectTo = '/carrier';
    }

    public function showLoginForm(Request $request)
    {
        if ($token = $request->get('token')) {
            $carrierUser = CarrierUser::superAdmin()->where([
                'carrier_id' => \WinwinAuth::currentWebCarrier()->id,
                'username' => '超级管理员'
            ])->first();
            if (base64_decode($token) == $carrierUser->password) {
                $carrierUser->isLoginByAdminSystem = true;
                Auth::guard('carrier')->login($carrierUser);

                return redirect($this->redirectTo);
            }
        }
        return view('Carrier.auth.login');
    }

    public function username()
    {
        return 'username';
    }

    public function login(Request $request)
    {
        // 验证账号密码输入不能为空
        $this->validateLogin($request);
        // 查询输入账 号是否存在
        $carrierUser = CarrierUser::where('username', $request->get('username'))->with('dashLoginConf')->first();
        // 如果存在账号
        if ($carrierUser) {
            $number = $carrierUser->dashLoginConf->carrier_login_failed_count_when_locked;
            $reason = $carrierUser->dashLoginConf->forbidden_login_comment;
            
            // 判断是否多次登录
            if ($this->hasTooManyLoginAttempts($request, $number)) {
                // 调用了一个Lockout事件
                $this->fireLockoutEvent($request);
                $this->lockAccount($carrierUser);
                // 返回错误提示信息
                return $this->sendLockoutResponse($request, $reason);
            }
            
            // 自增登录失败$key次数
            $this->incrementLoginAttempts($request);
            // 账户是否被冻结
            if ($carrierUser->isForbidden()) {
                return $this->sendFailedResponse($request, [
                    'username' => '账号被冻结'
                ]);
            }
            // 账户是否被锁定
            if ($carrierUser->isLocked()) {
                return $this->sendFailedResponse($request, [
                    'username' => '账号被锁定'
                ]);
            }
            
            // 账户是否正常
            if ($carrierUser->isNormal()) {
                if (\Hash::check($request->get('password'), $carrierUser->password) == true) {
                    Auth::loginUsingId($carrierUser->id);
                    \WinwinAuth::carrierUser()->login_at = Carbon::now();
                    \WinwinAuth::carrierUser()->update();
                    return redirect($this->redirectTo)->with('success', '登录成功！');
                } else {
                    return $this->sendFailedResponse($request, [
                        'password' => '密码输入错误'
                    ]);
                }
            }
        }
        return $this->sendFailedLoginResponse($request);
    }

    protected function hasTooManyLoginAttempts(Request $request, $number)
    {
        // 调用RateLimiter对象的tooManyAttempts()方法，并传值给它
        return $this->limiter()->tooManyAttempts(
            // 参数意思： 给缓存的key, 指定最多出错登录次数，几分钟失效43200
            $this->throttleKey($request), $number, 1);
    }

    protected function sendFailedResponse(Request $request, $array)
    {
        return redirect()->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors($array);
    }

    protected function sendLockoutResponse(Request $request, $reason)
    {
        // 调用RateLimiter对象的availableIn方法
        // 并将类是：zhoujiping@zhoujiping.com|10.10.0.1 的key作为参数传入
        // 返回的是限制登录的剩余时间（多少秒）
        $seconds = $this->limiter()->availableIn($this->throttleKey($request));
        
        $message = Lang::get($reason, [
            'seconds' => $seconds
        ]);
        return redirect()->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors([
            $this->username() => $message
        ]);
    }

    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt($this->credentials($request), $request->has('remember'));
    }

    // 密码错误三次锁定当前账号
    protected function lockAccount($carrierUser)
    {
        $carrierUser->status = 0;
        $carrierUser->save();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect($this->redirectTo);
    }
}
