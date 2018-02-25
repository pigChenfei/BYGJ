<?php

namespace App\Jobs;

use App\Models\AgentNews\AgentNewsRelation;
use App\Models\PlayerNews\PlayerNewsRelation;
use App\Models\System\ReminderEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendNewsAll implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;


    /**
     * @var ReminderEmail
     */
    private $carrier_id;

    private $person;

    private $type;

    private $news_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($carrier_id, $person, $type, $news_id)
    {
        $this->carrier_id = $carrier_id;
        $this->person = $person;
        $this->type = $type;
        $this->news_id = $news_id;
    }


    public function handle()
    {
        if ($this->type == 'player'){
            foreach ($this->person as $value) {
                $carrierPlat = new PlayerNewsRelation();
                $carrierPlat->player_news_id = $this->news_id;
                $carrierPlat->carrier_id = $this->carrier_id;
                $carrierPlat->player_id = $value;
                $carrierPlat->save();
            }
        }elseif ($this->type == 'agent'){
            foreach ($this->person as $value) {
                $carrierPlat = new AgentNewsRelation();
                $carrierPlat->agent_news_id = $this->news_id;
                $carrierPlat->carrier_id = $this->carrier_id;
                $carrierPlat->agent_id = $value;
                $carrierPlat->save();
            }
        }
    }

}
