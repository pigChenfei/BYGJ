<?php

namespace App\Jobs;

use App\Entities\CacheConstantPrefixDefine;
use App\Models\System\ReminderEmail;
use Curl\Curl;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateLogModelIpAddressQueue implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var \Eloquent
     */
    private $updateModel;

    private $ipField;

    private $addressField;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($updateModel, $ipField, $addressField)
    {
        $this->updateModel = $updateModel;
        $this->ipField     = $ipField;
        $this->addressField = $addressField;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $ipField = $this->ipField;
        $addressField = $this->addressField;
        $ip = $this->updateModel->$ipField;
        if(!$ip){
            \WLog::error('IP不存在',[ 'data' =>  $this->updateModel->toArray() ]);
            return;
        }
        $str = \Cache::get(CacheConstantPrefixDefine::IP_PLACE_CACHE_PREFIX.$ip);
        if(!$str){
            $curl = new Curl();
            $curl->get('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip='.$ip);
            if($curl->error){
                \WLog::error('IP获取失败:'.$ip.' error:'.$curl->errorMessage);
                $curl->close();
                return;
            }
            $data = $curl->response;
            $curl->close();
            $str = "";
            if(isset($data->country)){
                $str .= $data->country.' ';
            }
            if(isset($data->province)){
                $str .= $data->province.' ';
            }
            if(isset($data->city)){
                $str .= $data->city.' ';
            }
        }
        if($str){
            try{
                $this->updateModel->$addressField = $str;
                \Cache::put(CacheConstantPrefixDefine::IP_PLACE_CACHE_PREFIX.$ip,$str,60);
                $this->updateModel->update();
                \WLog::info('IP更新成功:'.$ip,['content' => $str]);
            }catch (\Exception $e){
                \WLog::info('IP更新失败:'.$ip,['error' => $e->getMessage()]);
                //dispatch(new SendReminderEmail(new ReminderEmail($e)));
            }
        }else{
            \WLog::error('IP无法获取到地区数据:'.$ip.' data:');
        }
    }
}
