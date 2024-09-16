<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PenghargaanSertifikat extends FormRequest
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
            'title' => 'required',
            'title_en' => 'required',
            'description' => 'required',
            'description_en' => 'required',
            'item_extras' => 'required',
            'item_file' => 'mimes:jpeg,png,jpg|max:100000'
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Title IDN is required',
            'title_en.required' => 'Title ENG is required',
            'description.required' => 'Description IDN is required',
            'description_en.required' => 'Description ENG is required',
            'item_extras.required' => 'Please select a year',
            'item_file.required' => 'Picture is required',
        ];
    }
}
