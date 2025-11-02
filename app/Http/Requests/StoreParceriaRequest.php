<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreParceriaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'instituicao' => ['required', 'string', 'max:255'],
            'contacto' => ['required', 'string', 'max:255'],
            'cargo' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'telefone' => ['required', 'string', 'max:30'],
            'tipo_parceria' => ['required', 'string'],
            'descricao' => ['required', 'string'],
            'website' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'instituicao.required' => 'O nome da instituição é obrigatório.',
            'contacto.required' => 'O nome da pessoa de contacto é obrigatório.',
            'cargo.required' => 'O cargo é obrigatório.',
            'email.required' => 'O E-mail é obrigatório.',
            'email.email' => 'Por favor, insira um E-mail válido.',
            'telefone.required' => 'O Telefone é obrigatório.',
            'tipo_parceria.required' => 'O tipo de parceria é obrigatório.',
            'descricao.required' => 'A descrição é obrigatória.',
        ];
    }
}
