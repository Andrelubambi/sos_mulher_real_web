<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVoluntarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            // Dados da Instituição / Parceria
            'instituicao' => ['required', 'string', 'max:255'],
            'contacto' => ['required', 'string', 'max:255'],
            'cargo' => ['required', 'string', 'max:255'],
            'tipo_parceria' => ['required', 'string'],
            'descricao' => ['required', 'string'],

            // Dados Pessoais do Voluntário
            'nome_completo' => ['required', 'string', 'max:255'],
            'data_nascimento' => ['required', 'date'],
            'telefone' => ['required', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:255'],
            'provincia' => ['required', 'string', 'max:255'],
            'profissao' => ['required', 'string', 'max:255'],
            'disponibilidade' => ['required', 'string', 'max:255'],

            // Motivação e Propósito
            'motivacao' => ['required', 'string'],
            'experiencia_previa' => ['required', 'in:sim,nao'],
            'descricao_experiencia' => ['nullable', 'string'],
            'areas_colaborar' => ['required', 'array'],
            'areas_colaborar.*' => ['string'],
        ];
    }

    public function messages(): array
    {
        return [
            // Dados da Instituição / Parceria
            'instituicao.required' => 'O campo Instituição é obrigatório.',
            'instituicao.string' => 'O campo Instituição deve ser um texto.',
            'instituicao.max' => 'O campo Instituição não pode exceder 255 caracteres.',

            'contacto.required' => 'O campo Contacto é obrigatório.',
            'contacto.string' => 'O campo Contacto deve ser um texto.',
            'contacto.max' => 'O campo Contacto não pode exceder 255 caracteres.',

            'cargo.required' => 'O campo Cargo é obrigatório.',
            'cargo.string' => 'O campo Cargo deve ser um texto.',
            'cargo.max' => 'O campo Cargo não pode exceder 255 caracteres.',

            'email.required' => 'O campo E-mail é obrigatório.',
            'email.email' => 'Por favor, insira um E-mail válido.',
            'email.max' => 'O campo E-mail não pode exceder 255 caracteres.',

            'telefone.required' => 'O campo Telefone é obrigatório.',
            'telefone.string' => 'O campo Telefone deve ser um texto.',
            'telefone.max' => 'O campo Telefone não pode exceder 30 caracteres.',

            'tipo_parceria.required' => 'O campo Tipo de Parceria é obrigatório.',
            'tipo_parceria.string' => 'O campo Tipo de Parceria deve ser um texto.',

            'descricao.required' => 'O campo Descrição é obrigatório.',
            'descricao.string' => 'O campo Descrição deve ser um texto.',

            // Dados Pessoais do Voluntário
            'nome_completo.required' => 'O campo Nome Completo é obrigatório.',
            'nome_completo.string' => 'O campo Nome Completo deve ser um texto.',
            'nome_completo.max' => 'O campo Nome Completo não pode exceder 255 caracteres.',

            'data_nascimento.required' => 'O campo Data de Nascimento é obrigatório.',
            'data_nascimento.date' => 'A Data de Nascimento deve estar num formato válido.',

            'provincia.required' => 'O campo Província é obrigatório.',
            'provincia.string' => 'O campo Província deve ser um texto.',
            'provincia.max' => 'O campo Província não pode exceder 255 caracteres.',

            'profissao.required' => 'O campo Profissão é obrigatório.',
            'profissao.string' => 'O campo Profissão deve ser um texto.',
            'profissao.max' => 'O campo Profissão não pode exceder 255 caracteres.',

            'disponibilidade.required' => 'O campo Disponibilidade é obrigatório.',
            'disponibilidade.string' => 'O campo Disponibilidade deve ser um texto.',
            'disponibilidade.max' => 'O campo Disponibilidade não pode exceder 255 caracteres.',

            // Motivação e Propósito
            'motivacao.required' => 'O campo Motivação é obrigatório.',
            'motivacao.string' => 'O campo Motivação deve ser um texto.',

            'experiencia_previa.required' => 'O campo Experiência Prévia é obrigatório.',
            'experiencia_previa.in' => 'O campo Experiência Prévia deve ser "sim" ou "não".',

            'descricao_experiencia.string' => 'A Descrição da Experiência deve ser um texto.',

            'areas_colaborar.required' => 'Deve selecionar pelo menos uma área para colaborar.',
            'areas_colaborar.array' => 'O campo Áreas de Colaboração deve ser um conjunto de valores.',
            'areas_colaborar.*.string' => 'Cada área selecionada deve ser um texto válido.',
        ];
    }
}
