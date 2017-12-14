<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTaskRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'title' => ['required', ],
            'body' => ['required', 'max:160']
            // 'message' => ['required', 'max:160']
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'El campo Title es requerido.',
            'body.required' => 'El campo Body es requerido.',
            'body.max' => 'El mensaje no puede superar los 160 caracteres.'
        ];
    }
}
