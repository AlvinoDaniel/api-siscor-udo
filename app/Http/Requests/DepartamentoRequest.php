<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DepartamentoRequest extends FormRequest
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
            'codigo'                => [
                "required",
                Rule::unique('departamentos')->ignore($this->route('id'))
            ],
            'nombre'                => [
                "required",
                Rule::unique('departamentos')->ignore($this->route('id'))
            ],
            'siglas'                => [
                "required",
                Rule::unique('departamentos')->ignore($this->route('id'))
            ],
            'correo'                => "nullable|email",
            'permiso_secretaria'    => "nullable|boolean",
        ];
    }
}
