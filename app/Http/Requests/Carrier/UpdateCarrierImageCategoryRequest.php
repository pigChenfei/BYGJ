<?php

namespace App\Http\Requests\Carrier;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Image\CarrierImageCategory;

class UpdateCarrierImageCategoryRequest extends FormRequest
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
        $id = \Route::current()->parameter('carrierImageCategory');
        return CarrierImageCategory::updateRules($id);
    }

    public function attributes()
    {
        return CarrierImageCategory::$requestAttribute;
    }
}
