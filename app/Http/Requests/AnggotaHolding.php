<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnggotaHolding extends FormRequest
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
            'id_content' => 'required',
            'id_content_order' => 'required',
            'url' => 'required',
            'subs_name' => 'required',
            'jenis_holding' => 'required',
            'item_file' => 'required|mimes:jpeg,png,jpg|max:100000'
        ];
    }

    public function messages()
    {
        return [
            'url.required' => 'Url is required',
            'subs_name.required' => 'Subsidiarie Name is required',
            'jenis_holding.required' => 'Please select one',
            'item_file.required' => 'Picture is required',
        ];
    }
}
