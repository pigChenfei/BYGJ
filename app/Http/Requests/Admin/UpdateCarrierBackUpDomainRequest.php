<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\CarrierBackUpDomain;

class UpdateCarrierBackUpDomainRequest extends FormRequest
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

   public function rules()
    {
       $id = $this->request->get('id');
        $carrier_rule = ['required'];
        $site_url_rule = ['unique:inf_carrier_back_up_domain,domain'];
        if($id){
            $site_url_rule = array_map(function($element) use ($id){
                return $element.','.$id;
            },$site_url_rule);
        }
        return [
            'carrier_id' => $carrier_rule,
            'domain' => array_merge($site_url_rule,['required','regex:/^((\w|-)+\.)+\w+$/']),
        ];
    }

    public function attributes()
    {
        return [
            'carrier_id' => '运营商',
            'domain' => '域名',
        ];
    }
}
