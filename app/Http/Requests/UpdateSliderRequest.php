<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSliderRequest extends FormRequest
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
            'upt_title' => 'required',
            'upt_description' => 'required',
            'upt_title_en' => 'required',
            'upt_description_en' => 'required',
            'upt_item_order' => 'required',
            'upt_item_file' => 'mimes:jpeg,png,jpg,mp4|max:100000'
        ];
    }

    public function messages()
    {
        return [
            'upt_title.required' => 'Title is required',
            'upt_description.required' => 'Description is required',
            'upt_title_en.required' => 'Title is required',
            'upt_description_en.required' => 'Description is required',
            'upt_item_order.required' => 'Please select an order',            
            'upt_item_file.required' => 'Picture is required',
        ];
    }
}
