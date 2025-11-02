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
            
            // Requer que a tabela 'voluntarios' exista na BD para verificação de unicidade
            'telefone' => ['required', 'string', 'max:30', 'unique:voluntarios,telefone'],
            'email' => ['required', 'email', 'max:255', 'unique:voluntarios,email'],
            
            'provincia' => ['required', 'string', 'max:255'],
            'profissao' => ['required', 'string', 'max:255'],
            'disponibilidade' => ['required', 'string', 'max:255'],

            'motivacao' => ['required', 'string'],
            'experiencia_previa' => ['required', 'in:sim,nao'],
            'descricao_experiencia' => ['nullable', 'string'],
            'areas_colaborar' => ['required', 'array'],
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
            // Corrigido para incluir a validação de formato de e-mail (resolvendo 'validation.email')
            'email.unique' => 'O E-mail inserido já se encontra registado.',
            'email.email' => 'O campo E-mail deve ser um endereço de e-mail válido.',
            'telefone.unique' => 'O Telefone inserido já se encontra registado.',
            'nome_completo.required' => 'O campo Nome Completo é obrigatório.',
            'data_nascimento.required' => 'A data de nascimento é obrigatória.',
            'provincia.required' => 'A província é obrigatória.',
            'profissao.required' => 'A profissão é obrigatória.',
            'disponibilidade.required' => 'A disponibilidade é obrigatória.',
            'motivacao.required' => 'A motivação é obrigatória.',
            'experiencia_previa.required' => 'A experiência prévia é obrigatória.',
            'areas_colaborar.required' => 'Selecione pelo menos uma área de colaboração.',
        ];
    }
}
