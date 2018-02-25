<?php

namespace App\Http\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Player;

class UpdatePlayerRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $player_id = \WinwinAuth::memberUser()->player_id;
        $player = Player::with('registerConf')->where('player_id',$player_id)->first();
        if($player){
            if((($player->registerConf->player_realname_conf_status & 2) == 2) && ($player->registerConf->is_check_exist_player_real_user_name == 1)){
                self::$rules = array_merge(self::$rules,[
                    'real_name'=>'string|min:2|max:12|unique:inf_player,real_name,'.$player_id.',player_id|required',///^[\u4e00-\u9fa5]{2,40}$/  ^([\u4e00-\u9fa5]+|([a-zA-Z]+\s?)+)$
                ]);
            }elseif ((($player->registerConf->player_realname_conf_status & 2) == 2) && ($player->registerConf->is_check_exist_player_real_user_name == 0)){
                self::$rules = array_merge(self::$rules,[
                    'real_name'=>'string|min:2|max:12|required',
                ]);
            }elseif ((($player->registerConf->player_realname_conf_status & 2) != 2) && ($player->registerConf->is_check_exist_player_real_user_name == 1)){
                self::$rules = array_merge(self::$rules,[
                    'real_name'=>'string|min:2|max:12|unique:inf_player,real_name,'.$player_id.',player_id',
                ]);
            }
            else{
                self::$rules = array_merge(self::$rules,[
                    'real_name'=>'string|min:2|max:12',
                ]);
            }

//            if(($player->registerConf->player_phone_conf_status & 2) == 2){
//                self::$rules = array_merge(self::$rules,[
//                    'mobile'=>'regex:/^1[3-8]\d{9}$/|unique:inf_player,mobile,'.$player_id.',player_id|required',
//                ]);
//            }else{
//                self::$rules = array_merge(self::$rules,[
//                    'mobile'=>'regex:/^1[3-8]\d{9}$/|unique:inf_player,mobile,'.$player_id.',player_id',
//                ]);
//            }

//            if(($player->registerConf->player_email_conf_status & 2) == 2){
//                self::$rules = array_merge(self::$rules,[
//                    'email'=>'email|unique:inf_player,email,'.$player_id.',player_id|required',
//                ]);
//            }else{
//                self::$rules = array_merge(self::$rules,[
//                    'email'=>'email|unique:inf_player,email,'.$player_id.',player_id',
//                ]);
//            }
            self::$rules = array_merge(self::$rules,[
                'mobile'=>'regex:/^1[3-9]\d{9}$/|unique:inf_player,mobile,'.$player_id.',player_id',
            ]);
            self::$rules = array_merge(self::$rules,[
                'email'=>'email|unique:inf_player,email,'.$player_id.',player_id',
            ]);
            if(($player->registerConf->player_birthday_conf_status & 2) == 2){
                self::$rules = array_merge(self::$rules,[
                    'birthday'=>'date|required',
                ]);
            }else{
                self::$rules = array_merge(self::$rules,[
                    'birthday'=>'date',
                ]);
            }

           /* if(($player->registerConf->player_qq_conf_status & 2) == 2){
                self::$rules = array_merge(self::$rules,[
                    'qq_account'=>'regex:/[1-9][0-9]{4,14}/|required',///[1-9][0-9]{4,14}/
                ]);
            }else{
                self::$rules = array_merge(self::$rules,[
                    'qq_account'=>'regex:/[1-9][0-9]{4,14}/',
                ]);
            }

            if(($player->registerConf->player_wechat_conf_status & 2) == 2){
                self::$rules = array_merge(self::$rules,[
                    'wechat'=>'regex:/^[a-zA-Z\d_]{5,}$/|required',
                ]);
            }else{
                self::$rules = array_merge(self::$rules,[
                    'wechat'=>'regex:/^[a-zA-Z\d_]{5,}$/',
                ]);
            }*/
            return self::$rules;
        }
        return self::$rules;
    }

    public static $rules = [
        /*'sex' => 'boolean|required',*/
    ];

    public function  attributes()
    {
        return Player::$requestAttributes;
    }
}
