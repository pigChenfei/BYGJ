<?php

namespace App\Repositories\Carrier;

use App\Models\Conf\CarrierWebSiteConf;
use InfyOm\Generator\Common\BaseRepository;

class CarrierWebSiteConfRepository extends BaseRepository
{

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'carrier_id',
        'site_title',
        'site_key_words',
        'site_description',
        'site_javascript',
        'site_notice',
        'site_footer_comment',
        'common_question_file_path',
        'contact_us',
        'privacy_policy_file_path',
        'rule_clause_file_path',
        'with_draw_comment_file_path',
        'net_bank_deposit_comment',
        'atm_deposit_comment',
        'third_part_deposit_comment',
        'commission_policy_file_path',
        'jointly_operated_agreement_file_path'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return CarrierWebSiteConf::class;
    }
}
