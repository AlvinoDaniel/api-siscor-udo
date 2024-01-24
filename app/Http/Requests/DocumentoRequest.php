<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DocumentoRequest extends FormRequest
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
            // 'asunto'                => "required",
            'contenido'             => "required",
            'tipo_documento'        => "required",
            'departamentos_destino' => "required|string",
            'departamentos_copias'  => "nullable|string",
            'copias'                => "required|boolean",
            'estatus'                => [
                "required",
                Rule::in(['enviar', 'borrador', 'corregir', 'enviar_all'])
            ],
        ];
    }
}
