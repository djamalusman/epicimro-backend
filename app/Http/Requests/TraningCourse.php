<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TraningCourse extends FormRequest
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
            'traning_name' => 'required',          
            'traning_name_en' => 'required',          
            'cetificate_type' => 'required',          
            'cetificate_type_en' => 'required',          
            'implementation_date' => 'required',          
            'training_duration' => 'required',          
            'facility' => 'required',
            'facility_en' => 'required',
            'training_material' => 'required',
            'training_material_en' => 'required',
            'item_file' => 'mimes:jpeg,png,jpg|max:100000'
        ];
    }

    public function messages()
    {
        return [

            'traning_name' => 'This column is Required',          
            'traning_name_en' => 'This column is Required',          
            'cetificate_type' => 'This column is Required',          
            'cetificate_type_en' => 'This column is Required',          
            'implementation_date' => 'This column is Required',          
            'training_duration' => 'This column is Required',          
            'facility' => 'This column is Required',
            'facility_en' => 'This column is Required',
            'training_material' => 'This column is Required',
            'training_material_en' => 'This column is Required',
        ];
    }
}
