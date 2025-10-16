<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Resend;
use Illuminate\Support\Facades\Log;

class ParceriaController extends Controller
{
    public function create()
    {
        return view('parceria');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'instituicao' => 'required|string|max:255',
            'contacto' => 'required|string|max:255',
            'cargo' => 'required|string|max:255',
            'email' => 'required|email',
            'telefone' => 'required|string|max:30',
            'tipo_parceria' => 'required|string',
            'descricao' => 'required|string',
            'website' => 'nullable|string|max:255',
        ]);

        // Log dos dados recebidos
        Log::info('📥 Formulário de parceria recebido:', $validated);

        // Envio automático do e-mail para a empresa
        $website = $validated['website'] ?? 'N/A';
        
        $mensagem = "💌 Novo pedido de parceria recebido:\n\n"
            . "🏢 Instituição: {$validated['instituicao']}\n"
            . "👩‍💼 Pessoa de Contacto: {$validated['contacto']}\n"
            . "📌 Cargo: {$validated['cargo']}\n"
            . "📧 E-mail: {$validated['email']}\n"
            . "📞 Telefone: {$validated['telefone']}\n"
            . "🤝 Tipo de parceria: {$validated['tipo_parceria']}\n"
            . "📝 Descrição: {$validated['descricao']}\n"
            . "🌐 Website/Redes: {$website}";

        // Email diferente para dev/prod
        $toEmail = app()->environment('production') 
            ? 'parcerias@sosmulherreal.com'
            : 'andrelubambi36@gmail.com';

        // Use a API Resend diretamente - USA CONFIG DO .ENV
        $resend = Resend::client(config('resend.api_key'));
        
        try {
            $result = $resend->emails->send([
                'from' => config('mail.from.address'), // Usa do .env
                'to' => [$toEmail],
                'subject' => 'Nova Solicitação de Parceria - SOS Mulher Real',
                'text' => $mensagem,
            ]);
            
            Log::info('✅ Email enviado com sucesso! ID: ' . $result->id);
            
            return back()->with('success', 'Formulário enviado com sucesso! Entraremos em contacto em breve.');
            
        } catch (\Exception $e) {
            Log::error('❌ Erro ao enviar email de parceria: ' . $e->getMessage());
            return back()->with('error', 'Erro ao enviar formulário. Tente novamente.');
        }
    }
}