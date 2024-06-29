<?php

namespace App\Http\Requests\Dokumen;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'nama_dokumen'                => 'required',
            'file_dokumen'                => 'required',
        ];
    }

    public function messages()
    {
        return [
            'nama_dokumen.required'               => 'Nama dokumen tidak boleh kosong',
            'file_dokumen.required'               => 'File dokumen tidak boleh kosong',
        ];
    }
}
