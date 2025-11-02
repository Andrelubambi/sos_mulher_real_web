<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreParceriaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'instituicao'   => ['required', 'string', 'max:255'],
            'contacto'      => ['required', 'string', 'max:255'],
            'cargo'         => ['required', 'string', 'max:255'],
            'email'         => ['required', 'email', 'max:255'],
            'telefone' => ['required', 'numeric', 'regex:/^9\d{8}$/', 'unique:voluntarios,telefone'],
            'tipo_parceria' => ['required', 'array', 'min:1'],
            'descricao'     => ['required', 'string'],
            'website'       => ['nullable', 'string', 'max:255'
        ],
        ];
    }

    public function messages(): array
    {
        return [
            // Instituição
            'instituicao.required' => 'O nome da instituição é obrigatório.',
            'instituicao.string'   => 'O valor do campo Instituição é inválido. Deve conter apenas texto.',
            'instituicao.max'      => 'O nome da instituição não pode ter mais de 255 caracteres.',

            // Pessoa de contacto
            'contacto.required' => 'O nome da pessoa de contacto é obrigatório.',
            'contacto.string'   => 'O valor do campo Pessoa de Contacto é inválido. Deve conter apenas texto.',
            'contacto.max'      => 'O nome da pessoa de contacto não pode ter mais de 255 caracteres.',

            // Cargo
            'cargo.required' => 'O cargo é obrigatório.',
            'cargo.string'   => 'O valor do campo Cargo é inválido. Deve conter apenas texto.',
            'cargo.max'      => 'O cargo não pode ter mais de 255 caracteres.',

            // Email
            'email.required' => 'O e-mail é obrigatório.',
            'email.email'    => 'O e-mail inserido não é válido. Exemplo válido: exemplo@dominio.com.',
            'email.max'      => 'O e-mail não pode ter mais de 255 caracteres.',

            // Telefone
             'telefone.unique' => 'O Telefone inserido já se encontra registado.',
            'telefone.required' => 'O Telefone é obrigatório.',
            'telefone.numeric' => 'O Telefone deve conter apenas números.',
            'telefone.max' => 'O Telefone não pode ter mais de :max dígitos.',
            

            // Tipo de Parceria
            'tipo_parceria.required' => 'Selecione pelo menos um tipo de parceria.',
            'tipo_parceria.array'    => 'O tipo de parceria deve ser uma lista válida.',
            'tipo_parceria.min'      => 'Selecione pelo menos um tipo de parceria.',

            // Descrição
            'descricao.required' => 'A descrição é obrigatória.',
            'descricao.string'   => 'O valor da descrição é inválido. Deve conter apenas texto.',

            // Website
            'website.string' => 'O website ou link inserido é inválido.',
            'website.max'    => 'O link não pode ter mais de 255 caracteres.',
        ];
    }
}
