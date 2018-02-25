<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Carrier;

class CreateCarrierRequest extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->request->get('id');
        $site_url_rule = ['unique:inf_carrier,site_url','unique:inf_carrier_back_up_domain,domain'];
        $name_rule = ['required','unique:inf_carrier,name'];
        if($id){
            $site_url_rule = array_map(function($element) use ($id){
                return $element.','.$id;
            },$site_url_rule);
            $name_rule = ['required','unique:inf_carrier,name,'.$id];
        }
        return [
            'name' => $name_rule,
            'site_url' => array_merge($site_url_rule,['required','regex:/^((\w|-)+\.)+\w+$/']),
            'remain_quota' => 'required|min:0|numeric',
        ];
    }

    public function attributes()
    {
        return [
            'name' => '运营商名称',
            'site_url' => '域名',
            'remain_quota' => '额度'
        ];
    }
}

