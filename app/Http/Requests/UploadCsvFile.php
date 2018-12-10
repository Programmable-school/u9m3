<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadCsvFile extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.

     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**

     * @return array
     */
    public function rules()
    {
        return [

