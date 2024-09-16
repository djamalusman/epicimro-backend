<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAnggotaHolding extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [  
            'upt_id' => 'required',          
            'upt_url' => 'required',
            'upt_item_order' => 'required',
            'upt_item_file' => 'mimes:jpeg,png,jpg|max:100000'
        ];
    }

    public function messages()
    {
        return [
            'upt_url.required' => 'Url is required',
            'upt_item_order.required' => 'Please select an order',            
            'upt_item_file.required' => 'Picture is required',
        ];
    }
}
