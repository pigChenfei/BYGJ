<?php

namespace App\Notifications;

use App\Models\Log\PlayerWithdrawLog;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CarrierPlayerWithdrawNotification extends Notification
{
    use Queueable;


    /**
     * @var PlayerWithdrawLog
     */
    private $withdrawLog;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(PlayerWithdrawLog $log)
    {
        $this->withdrawLog = $log;
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


    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return $this->withdrawLog->toArray();
    }
}
