<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KontakProdukDanLayanan extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
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
            'address'            => 'required',
            'address_en'            => 'required',
            // 'instagram_link'            => 'required',
            // 'facebook_link'            => 'required',
            // 'youtube_link'            => 'required',
            // 'email_link'            => 'required',
            // 'website_link'            => 'required',
            'phone_number_one'            => 'required',
            
        ];
    }

    public function messages()
    {
        return [
            'address.required'   => 'Address is Required',
            'address_en.required'   => 'Address is Required',
            'instagram_link.required'   => 'Instagram link is Required',
            'facebook_link.required'   => 'Facebook link is Required',
            'youtube_link.required'   => 'Youtube link is Required',
            'email_link.required'   => 'Email link is Required',
            'website_link.required'   => 'Website url is Required',
            'phone_number_one.required'   => 'Phone number is Required',
        ];
    }
}
