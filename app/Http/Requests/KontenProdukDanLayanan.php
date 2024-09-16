<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KontenProdukDanLayanan extends FormRequest
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
            'title_id'            => 'required',
            'title_eng'         => 'required',
            'description'      => 'required',
            'description_en'   => 'required',
            'description2'      => 'required',
            'description2_en'   => 'required',
        ];
    }

    public function messages()
    {
        return [
            'title_id.required'            => 'Company Name is Required',
            'title_eng.required'         => 'Company Name is Required',
            'description.required'      => 'Description is Required',
            'description_en.required'   => 'Description is Required',
            'description2.required'      => 'Visi is Required',
            'description2_en.required'   => 'Visi is Required',
        ];
    }
}
