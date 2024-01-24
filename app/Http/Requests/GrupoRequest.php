<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class GrupoRequest extends FormRequest
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
            'nombre'            => [
                'required',
                Rule::unique('grupos')->where(function ($query) {
                    return $query->where('departamento_id', Auth::user()->personal->departamento_id);
                })
            ],
            'departamentos'     => 'required|string',
        ];
    }
}
