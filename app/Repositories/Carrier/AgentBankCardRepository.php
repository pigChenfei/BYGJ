<?php

namespace App\Repositories\Carrier;

use App\Models\Carrier\AgentBankCard;
use InfyOm\Generator\Common\BaseRepository;

class AgentBankCardRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'carrier_id',
        'agent_id',
        'card_account',
        'card_type',
        'card_owner_name',
        'card_birth_place',
        'status'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return AgentBankCard::class;
    }
}
