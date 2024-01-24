<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PersonalRequest extends FormRequest
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
            'cedula_identidad'  => [
                "required",
                "numeric",
                Rule::unique('personal')->ignore($this->route('id'))
            ],
            'departamento_id'   => [
                "required",
                "exists:departamentos,id",
            ],
            'nombres_apellidos'   => "required",
            'cargo'     => "required",
            'nucleo'    => "required",
            'correo'    => "nullable|email",
            'firma'     => "nullable|image",
        ];
    }
}
