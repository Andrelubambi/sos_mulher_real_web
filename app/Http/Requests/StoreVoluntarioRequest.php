<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVoluntarioRequest extends FormRequest
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
            'nome_completo' => ['required', 'string', 'max:255'],
            'data_nascimento' => ['required', 'date'],
            
            // Regra corrigida para 'numeric' em vez de 'string'
            'telefone' => ['required', 'numeric', 'regex:/^9\d{8}$/', 'unique:parcerias,telefone'],
            'email' => ['required', 'email', 'max:255', 'unique:voluntarios,email'],
            
            'provincia' => ['required', 'string', 'max:255'],
            'profissao' => ['required', 'string', 'max:255'],
            'disponibilidade' => ['required', 'string', 'max:255'],

            'motivacao' => ['required', 'string'],
            'experiencia_previa' => ['required', 'in:sim,nao'],
            'descricao_experiencia' => ['nullable', 'string'],
            
            // Regra ajustada para ser um array com mínimo de 1 item
            'areas_colaborar' => ['required', 'array', 'min:1'],
            'areas_colaborar.*' => ['string'],
            'outras_areas' => ['nullable', 'string', 'max:255'],
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
            // Validação de E-mail
            'email.unique' => 'O E-mail inserido já se encontra registado.',
            'email.email' => 'O campo E-mail deve ser um endereço de E-mail válido.',
            
            // Validação de Telefone (adicionadas mensagens para 'numeric', 'unique', e 'max')
            'telefone.required' => 'O Telefone é obrigatório.',
'telefone.numeric' => 'O Telefone deve conter apenas números.',
'telefone.regex' => 'O Telefone deve ter 9 dígitos e começar com o número 9 (ex: 923456789).',
'telefone.unique' => 'O Telefone inserido já se encontra registado.',

            
            // Campos Obrigatórios
            'nome_completo.required' => 'O campo Nome Completo é obrigatório.',
            'data_nascimento.required' => 'A data de nascimento é obrigatória.',
            'provincia.required' => 'A província é obrigatória.',
            'profissao.required' => 'A profissão é obrigatória.',
            'disponibilidade.required' => 'A disponibilidade é obrigatória.',
            'motivacao.required' => 'A motivação é obrigatória.',
            'experiencia_previa.required' => 'A experiência prévia é obrigatória.',
            
            // Validação de Áreas de Colaboração (adicionadas mensagens para 'array' e 'min')
            'areas_colaborar.required' => 'É obrigatório selecionar pelo menos uma área de colaboração.',
            'areas_colaborar.array' => 'Selecione pelo menos uma área de colaboração.',
            'areas_colaborar.min' => 'É obrigatório selecionar pelo menos uma área de colaboração.',
        ];
    }
}
