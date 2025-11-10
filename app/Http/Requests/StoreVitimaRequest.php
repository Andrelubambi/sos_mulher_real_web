<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVitimaRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            // Requer que a tabela 'users' exista na BD
            'email' => ['required', 'email', 'unique:users,email'],
            // CORRIGIDO: O telefone deve ser 'numeric' e ter um limite máximo
           // 'telefone' => ['required', 'numeric', 'max:30', 'unique:users,telefone'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', 'in:vitima'], // Garantir que a role é 'vitima'
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
            'email.required' => 'O campo E-mail é obrigatório.',
            'email.email' => 'Por favor, insira um E-mail válido.',
            'email.unique' => 'Este E-mail já está registado.',
            
            // CORRIGIDO: Mensagens específicas para numeric e max
            'telefone.required' => 'O campo Telefone é obrigatório.',
            'telefone.unique' => 'Este Telefone já está registado.',
            'telefone.numeric' => 'O Telefone deve conter apenas números.',
            'telefone.max' => 'O Telefone não pode ter mais de :max dígitos.',
            
            'name.required' => 'O campo Nome é obrigatório.',
            'password.required' => 'O campo Password é obrigatório.',
            'password.min' => 'A password deve ter pelo menos 6 caracteres.',
            
            'role.required' => 'A função (role) é obrigatória.',
            'role.in' => 'A função fornecida não é válida.',
        ];
    }
}
