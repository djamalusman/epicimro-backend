<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VisiMisi extends FormRequest
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
            'title_eng' => 'required',
            'description' => 'required',
            'description_en' => 'required',
            'item_file' => 'mimes:jpeg,png,jpg|max:100000'
        ];
    }

    public function messages()
    {
        return [
            'title_id.required' => 'This column is Required',
            'title_eng.required' => 'This column is Required',
            'description.required' => 'Description is required',
            'description_en.required' => 'Description is required',
        ];
    }
}
