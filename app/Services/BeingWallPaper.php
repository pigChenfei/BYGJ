<?php
/**
 * Created by PhpStorm.
 * User: wugang
 * Date: 2017/4/13
 * Time: 下午1:18
 */

namespace App\Services;


class BeingWallPaper
{

    public static function getPaper(){
        return \Cache::remember('BEING_WALL_PAPER_'.date('Y-m-d'),1440,function (){
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, "http://www.bing.com/HPImageArchive.aspx?format=js&idx=0&n=10&mkt=en-US");
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($curl);
            curl_close($curl);
            $json_data = json_decode($result, true);
            try{
                return array_map(function($element){
                    return 'http://cn.bing.com'.$element['url'];
                },$json_data['images']);
            }catch (\Exception $e){
                return null;
            }
        });
    }

}