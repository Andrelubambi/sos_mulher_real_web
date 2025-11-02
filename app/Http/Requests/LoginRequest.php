<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // O login é uma ação que qualquer utilizador não autenticado deve poder realizar
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
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
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
            // Validações de E-mail
            'email.required' => 'O campo E-mail é obrigatório.',
            'email.email' => 'Por favor, insira um E-mail válido.',
            
            // Validações de Password
            'password.required' => 'O campo Password é obrigatório.',
            // Mensagem explícita para o tipo, evitando chaves genéricas como 'validation.string'
            'password.string' => 'A password deve ser um texto válido.', 
        ];
    }
}
