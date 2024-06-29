<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'name'                => 'required',
            'email'               => 'required',
            'password'            => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required'               => 'Nama tidak boleh kosong',
            'email.required'              => 'Email tidak boleh kosong',
            'password.required'           => 'Password tidak boleh kosong',
        ];
    }
}
