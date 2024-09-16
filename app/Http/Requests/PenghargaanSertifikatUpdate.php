<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PenghargaanSertifikatUpdate extends FormRequest
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
            'upt_title' => 'required',
            'upt_title_en' => 'required',
            'upt_description' => 'required',
            'upt_description_en' => 'required',
            'upt_item_extras' => 'required',
            'upt_item_file' => 'mimes:jpeg,png,jpg|max:100000'
        ];
    }

    public function messages()
    {
        return [
            'upt_title.required' => 'Title IDN is required',
            'upt_title_en.required' => 'Title ENG is required',
            'upt_description.required' => 'Description IDN is required',
            'upt_description_en.required' => 'Description ENG is required',
            'upt_item_extras.required' => 'Please select a year',
            'upt_item_file.required' => 'Picture is required',
        ];
    }
}
