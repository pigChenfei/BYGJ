<?php

namespace App\Http\Controllers\Carrier\Auth;

use App\Models\CarrierUser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
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

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/carrier/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255|unique:inf_carrier_user,username',
            'email' => 'required|email|max:255|unique:inf_carrier_user',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    public function showRegistrationForm()
    {
        return view('Carrier.auth.register');
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return CarrierUser
     */
    protected function create(array $data)
    {
        //dd($data);
        return CarrierUser::create([
            'carrier_id' => 1,   //Todo:  此处运营商id应该根据域名进行判断,暂时测试写为1
            'username' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }


}
