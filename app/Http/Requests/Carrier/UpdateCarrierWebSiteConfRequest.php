<?php

namespace App\Http\Requests\Carrier;

use App\Models\Conf\CarrierWebSiteConf;
use App\Models\Image\CarrierImage;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateCarrierWebSiteConfRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }


    /**
     * 由于网站配置分成了很多块, 所以对不同的区块进行分别验证
     * @var array
     */
    private $rules = [
        'base_info' => [
            'site_title' => 'required|max:40',
            'site_key_words' => 'max:100',
            'site_description' => 'max:200',
            'net_bank_deposit_comment' => 'max:255',
            'atm_deposit_comment' => 'max:255',
            'third_part_deposit_comment' => 'max:255',
            'activity_image_resolution_width' => 'integer|min:0|max:65535',
            'activity_image_resolution_height' => 'integer|min:0|max:65535'
        ],
        'content_info' => [
            'site_notice' => 'max:500',
            'site_footer_comment' => 'max:500',
            'site_javascript' => 'max:1024',
            'bannerImagesData' => 'json'
        ],
        'common_questions' => [
            'common_questions' => 'max:10000'
        ],
        'privacy_policy' => [
            'privacy_policy' => 'max:10000'
        ],
        'rule_clause' => [
            'rule_clause' => 'max:10000'
        ],
        'commission_policy' => [
            'commission_policy' => 'max:10000'
        ],
        'jointly_operated_agreement' => [
            'jointly_operated_agreement' => 'max:10000'
        ],
        'with_draw_comment' => [
            'with_draw_comment' => 'max:10000'
        ],
        'contact_us' => [
            'contact_us' => 'max:10000'
        ],
        'about_us' => [
            'about_us' => 'max:10000'
        ],
        'duty' => [
            'duty' => 'max:10000'
        ],
        'agent_pattern' => [
            'agent_pattern' => 'max:10000'
        ],
        'agent_index' => [
            'agent_index' => 'max:10000'
        ],
        'mobile_about' => [
            'mobile_about' => 'max:10000'
        ],
        'mobile_contact' => [
            'mobile_contact' => 'max:10000'
        ],
        //总后台配置
//        'online_service' => [
//            'online_service' => 'max:10000'
//        ],
    ];


    protected function getValidatorInstance()
    {
        $validator = parent::getValidatorInstance();
        $validator->after(function () use ($validator) {
            if ($this->get('update_type') == null) {
                $validator->errors()->add('update_type', '非法请求');
                return;
            }
            if (!in_array($this->get('update_type'), array_keys($this->rules))) {
                $validator->errors()->add('update_type', '非法请求');
                return;
            }

            if($bannerImagesData = $this->get('bannerImagesData')){
                $bannerImageArray = json_decode($bannerImagesData,true);
                $bannerImageIds = array_map(function($element){
                    return $element['id'];
                },CarrierImage::select('id')->get()->toArray());
                $bannerTypes    = array_keys(CarrierWebSiteConf::sitePages());
                foreach ($bannerImageArray as $element){
                    if(!isset($element['selectedImageValue']) || !isset($element['selectedBannerPages'])){
                        $validator->errors()->add('bannerImagesData', 'Banner图片数据不能为空');
                        return;
                    }
                    if(!in_array($element['selectedImageValue'],$bannerImageIds)){
                        $validator->errors()->add('bannerImagesData', 'Banner图片不存在');
                        return;
                    }
                    foreach ($element['selectedBannerPages'] as $pageTypeId){
                        if(!in_array($pageTypeId,$bannerTypes)){
                            $validator->errors()->add('bannerImagesData', 'Banner页面不存在');
                            return;
                        }
                    }
                }
            }

        });
        return $validator;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $requestType = $this->get('update_type', 'base_info');
        $type = [
            'update_type' => 'required'
        ];
        if (!in_array($this->get('update_type'), array_keys($this->rules))) {
            return $type;
        }
        return array_merge($type, $this->rules[$requestType]);
    }

    public function attributes()
    {
        return [
            'site_title' => '站点标题',
            'site_key_words' => '站点关键字',
            'site_description' => '站点描述',
            'contact_us' => '联系我们',
            'about_us' => '关于我们',
            'duty' => '责任博彩',
            'net_bank_deposit_comment' => '网银存款提示',
            'atm_deposit_comment' => 'ATM存款提示',
            'third_part_deposit_comment' => '第三方存款提示',
            'site_notice' => '站点通知',
            'site_footer_comment' => '底部说明',
            'site_javascript' => 'Javascript脚本',
            'common_questions' => '常见问题',
            'privacy_policy' => '隐私政策',
            'rule_clause' => '规则条款',
            'commission_policy' => '佣金政策',
            'jointly_operated_agreement' => '合营协议',
            'with_draw_comment' => '取款说明',
            'bannerImagesData' => 'Banner图片'
        ];
    }
}
