<?php

namespace App\Http\Requests\Carrier;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Image\CarrierImage;

class CreateCarrierImageRequest extends FormRequest
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
        if (!$this->request->get('id')){
            return CarrierImage::$rules;
        }
        return [
            'image_category' => 'required|exists:inf_carrier_image_category,id',
//            'image_path' => 'required',
        ];
    }
}
