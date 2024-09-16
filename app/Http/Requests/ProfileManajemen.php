<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileManajemen extends FormRequest
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
            'title_id' => 'required',
            'description' => 'required',
            'description_en' => 'required',
            'description2' => 'required',
            'description2_en' => 'required',
            'item_file' => 'mimes:jpeg,png,jpg|max:100000'
        ];
    }

    public function messages()
    {
        return [
            'title_id.required' => 'Name is Required',
            'description.required' => 'Position is required',
            'description_en.required' => 'Position is required',
            'description2.required' => 'Description is required',
            'description2_en.required' => 'Description is required',
        ];
    }
}
