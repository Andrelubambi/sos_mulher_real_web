<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMedicoRequest extends FormRequest
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
            // Requer que a tabela 'users' exista na BD e use regex
            'telefone' => ['required', 'unique:users,telefone', 'regex:/^(\+?[1-9]{1,4}[\s-]?)?(\(?\d{1,3}\)?[\s-]?)?[\d\s-]{5,15}$/'],
            'password' => ['required', 'string', 'min:6'],
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
            
            'telefone.required' => 'O campo Telefone é obrigatório.',
            'telefone.unique' => 'Este Telefone já está registado.',
            'telefone.regex' => 'O formato do telefone não é válido.',
            
            'name.required' => 'O campo Nome é obrigatório.',
            'password.required' => 'O campo Password é obrigatório.',
            'password.min' => 'A password deve ter pelo menos 6 caracteres.',
        ];
    }
}
