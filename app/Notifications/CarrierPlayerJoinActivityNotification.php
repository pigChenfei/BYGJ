<?php

namespace App\Notifications;

use App\Models\CarrierActivity;
use App\Models\CarrierActivityAudit;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CarrierPlayerJoinActivityNotification extends Notification
{
    use Queueable;


    /**
     * @var CarrierActivity
     */
    private $activity;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(CarrierActivity $activity)
    {
        $this->activity = $activity;
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
    public function toDatabase($notifiable)
    {
        return $this->activity->toArray();
    }
}
