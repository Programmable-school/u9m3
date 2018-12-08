<?php

namespace App\Http\Requests;

use Illuminate\Foundation\FormRequest;

class UploadCsvFile extends FormRequest
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
     * Get the validation rules that apply to the request
     * 
     * @return array
     */
    public function rules()
    {
        return [
            'csvfile' => [
                'required',
                'file',
                'mimetypes:text/plain', //ファイルタイプは text であること
            ],
        ];
    }
}