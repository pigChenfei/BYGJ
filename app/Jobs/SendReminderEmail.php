<?php

namespace App\Jobs;

use App\Models\System\ReminderEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendReminderEmail implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;


    /**
     * @var ReminderEmail
     */
    private $reminderEmail;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ReminderEmail $reminderEmail)
    {
        $this->reminderEmail = $reminderEmail;
    }


    public function handle()
    {
//        $errorMessage = $this->errorMessage();
//        $destinations = $this->destinations();
//        $view = $this->getView();
//        $data = $this->data();
//        try{
//            \Mail::queue($view,$data, function ($message) use ($errorMessage,$destinations) {
//                foreach ($destinations as $destination){
//                    \Mail::to($destination)->subject($errorMessage);
//                }
//            });
//        }catch (\Exception $e){
//            \WLog::error('======>邮件发送失败:'.$e->getMessage());
//        }
//        \WLog::error('PT测试:',['message' => $this->errorMessage()]);
    }

    public function failed(\Exception $e)
    {
        \WLog::error('======>队列处理失败',['message' => $e->getMessage()]);
    }

    private function data(){
        return ['trace' => $this->reminderEmail->errorDebugTrace];
    }

    private function getView(){
        return 'errors.500';
    }

    private function errorMessage(){
        return $this->reminderEmail->errorMessage;
    }

    private function destinations(){
        return \Config::get('app.system_accept_emails');
    }
}
