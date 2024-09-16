<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class YoutubeRequest extends FormRequest
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
            'title_youtube' => 'required',
            'description_youtube' => 'required',
            'title_youtube_en' => 'required',
            'description_youtube_en' => 'required',
            'url_youtube' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'title_youtube.required' => 'Title is required',
            'description_youtube.required' => 'Description is required',
            'title_youtube_en.required' => 'Title is required',
            'description_youtube_en.required' => 'Description is required',
            'url_youtube.required' => 'Url is required'
        ];
    }
}
