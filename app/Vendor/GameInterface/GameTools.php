<?php
namespace App\Vendor\GameInterface;

use App\Vendor\GameInterface\BBin;
use App\Vendor\GameInterface\PT;

/**
 * 游戏相关接口工厂
 */
class GameTools
{
    /**
     * @mainPlat
     */
    const PT        = 'pt';
    const BBin      = 'bbin';
    const SB        = 'sb';
    const MG        = 'mg';
    const AG        = 'ag';
    const MA        = 'main';
    const OW        = 'onworks';
    const GD        = 'gd';
    const TGP       = 'tgp';
    const VR        = 'vr';
    const TTG       = 'ttg';
    const MT        = 'mt';
    const PNG       = 'png';

    public static $game_map = [
         'pt' => PT::class,
         'bbin' => BBin::class
      //  'ag' => AG::class,
       // 'mg' => MG::class,
       
       // 'mg' => MG::class,
       // 'sb' => SB::class,
       // 'vr' => VR::class,
       // 'png'=> PNG::class,
       // 'gd' => GD::class,
       // 'tgp'=> TGP::class,
       // 'ttg'=> TTG::class,
        
       // 'onworks'=>ONWORKS::class
    ];
  
    //创建用户,前辍保留为五个字符,BBIN为20个字符
    //PT用户:TTC1ZNHY9HM9IRE ,密码：dmugrvtm
    //BBin用户：ttc1ZHL1zDhtXRs
    /*
    *@result=array('flag'=>'','username'=>'','password'=>'')
    *创建用户
    */
    public function createMember($mainPlat,$carrierId)
    {
        $game               = new self::$game_map[$mainPlat]();
        $mainGamePlat       = \DB::table('def_main_game_plats')->where('main_game_plat_code',$mainPlat)->first();
        $number             = 1;
       do
       {
            $number++;
            $accountUserName    = $mainGamePlat->account_pre.$carrierId.'Z'.$this->generate_value(10);
            //$accountUserName    ='ttc1ZHL1zDhtXRs';
            $result=$game->createMember($accountUserName);
            if($number==3)  break;
        }while($result['flag']=='false');

        return $result;
    }

    /*
    *@result=array('flag'=>'')
    *游戏踢线
    */
    public function kick($mainPlat,$accountUserName)
    {
        $game =new self::$game_map[$mainPlat]();
        return $game->kick($accountUserName);
    }

    
    /*
    *@result=array('flag'=>'','balance'=>'')
    *查询余额
    */
    public function checkBalance($mainPlat,$accountUserName)
    {
         $game =new self::$game_map[$mainPlat]();
         return $game->checkBalance($accountUserName);
    }

    /*
    *@result=array('flag'=>'','orderNumber'=>'','balance'=>'')
    *@flag=true,false,unknown
    *转入游戏平台
    */
    public function transferIn($mainPlat,$accountUserName,$money)
    {
        $game =new self::$game_map[$mainPlat]();
        return $game->transferIn($accountUserName,$money);
    }

    /*
    *@result=array('flag'=>'','orderNumber'=>'','balance'=>'')
    *@flag=true,false,unknown
    *转出游戏平台
    */
    public function transferOut($mainPlat,$accountUserName,$money)
    {
        $game =new self::$game_map[$mainPlat]();
        return $game->transferOut($accountUserName,$money);
    }

    /*
    *进入PC版游戏
    */
    public function playgamePc($mainPlat,$param)
    {
        $game =new self::$game_map[$mainPlat]();
        $game->playgamePc($param);
    }

    /*
    *进入移动版游戏
    */
    public function playgameMobile($mainPlat,$param)
    {
        $game =new self::$game_map[$mainPlat]();
        return $game->playgameMobile($param);
    }

    /*
    *进入游戏大厅
    */
    public function loginhall($mainPlat,$param)
    {
        $game =new self::$game_map[$mainPlat]();
        return $game->loginhall($param);
    }

    /*
    *进入移动版大厅
    */
    public function loginhallMobile($mainPlat,$param)
    {
        $game =new self::$game_map[$mainPlat]();
        return $game->loginhallMobile($param);
    }

    //进入试玩游戏
    public function trial($mainPlat,$param)
    {
        $game =new self::$game_map[$mainPlat]();
        return $game->trial($param);
    }
    /*
    *@result=array('flag'=>'')
    *@flag=true,false,unknown
    *检查订单状态
    */
    public function checkTransfer($mainPlat,$orderNumber)
    {
        $game =new self::$game_map[$mainPlat]();
        return $game->checkTransfer($orderNumber);
    }

    //查询下注流水
    public function betRecord($mainPlat,$time)
    {
        $game =new self::$game_map[$mainPlat]();
        return $game->betRecord($time);
    }

    //生成随机字符串
    private function generate_value( $length = 8 )
    {
        // 密码字符集，可任意添加你需要的字符
        $chars ='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $value ='';
        for ( $i = 0;$i < $length; $i++ )
        {
            $value .= $chars[ mt_rand(0, strlen($chars) - 1) ];
        }
        return $value;
    }
}