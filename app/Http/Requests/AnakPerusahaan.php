<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnakPerusahaan extends FormRequest
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
            'item_file_2' => 'mimes:jpeg,png,jpg|max:100000',
            'item_file_en' => 'mimes:jpeg,png,jpg|max:100000'
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Title IDN is required',
            'title_en.required' => 'Title ENG is required',
            'item_file_en.required' => 'Picture is required',
            'item_file_2.required' => 'Picture is required',
        ];
    }
}
