<?php

namespace App\Notifications;

use App\Models\Log\PlayerDepositPayLog;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class CarrierPlayerDepositNotification extends Notification implements ShouldQueue
{
    use Queueable;
    /**
     * @var PlayerDepositPayLog
     */
    private $depositLog;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(PlayerDepositPayLog $depositLog)
    {
        $this->depositLog = $depositLog;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable){
        return $this->depositLog->toArray();
    }

}
