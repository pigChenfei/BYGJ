<?php

namespace App\Http\Controllers\Agent\Auth;
use App\Http\Controllers\AppBaseController;
use App\Models\CarrierAgentUser;
use App\Http\Controllers\Controller;
use App\Models\Conf\CarrierDashLoginConf;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends AppBaseController
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

//    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/agent/admin/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

        $this->middleware('guest:agent');
    }


    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $agentRegisterConf = \WinwinAuth::currentWebAgent()->carrier->dashLoginConf;
        //TODO 根据配置项定义规则
        return Validator::make($data, [
            'username' => 'required|max:255|unique:inf_agent,username',
            'password' => 'required|min:6|max:20'
        ]);

    }

    public function showRegistrationForm()
    {
        //获得代理类型下的所有代理
        $carrierAgentLevelName = \App\Models\CarrierAgentLevel::where('type',1)->get();
        $agentRegisterConf = \WinwinAuth::currentWebAgent()->carrier->dashLoginConf;
        //判断运营商是否禁止注册
        if($agentRegisterConf->is_allow_agent_register == 0) {
            return view('agent.auth.banRegister');
        }
        return view('agent.auth.register')->with(['agentRegisterConf'=>$agentRegisterConf,'carrierAgentLevelName'=>$carrierAgentLevelName]);
    }

    
    public function register(Request $request)
    {
        $input = $request->all();
        $this->validator($request->all())->validate();

        //代理商父ID
        $input['parent_id'] = \WinwinAuth::currentWebAgent()->id;
        //运营商ID
        $input['carrier_id'] = \WinwinAuth::currentWebAgent()->carrier_id;


        $user = $this->create($input);

        if ($user){
            \WinwinAuth::agentAuth()->loginUsingId($user->id);
            return $this->sendSuccessResponse(route('/'));
        }


       // $this->guard()->login($user);
//        return $this->registered($request, $user)
//            ?: redirect($this->redirectPath());
    }
    
    protected function guard()
    {
        return Auth::guard();
    }
    
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return CarrierAgentUser::create([
            'username' => $data['username'],
            'password' => bcrypt($data['password']),
            'realname' =>  $data['real_name'],
            'agent_level_id' => $data['agent_level_id'],
            'birthday' => $data['birthday'],
            'email' => $data['email'],
            'mobile' => $data['mobile'],
            'skype' => $data['skype'],
            'qq' => $data['qq'],
            'wechat' => $data['wechat'],
            'promotion_url' => $data['promotion_url'],
            'promotion_notion' => $data['promotion_notion'],
            'promotion_code' => $data['promotion_code'],
            'parent_id' => $data['parent_id'],
            'carrier_id' => $data['carrier_id']
        ]);
    }

}
