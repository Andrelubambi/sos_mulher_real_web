<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Resend;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreVoluntarioRequest;


class VoluntarioController extends Controller
{
     public function create()
    {
        return view('auth.voluntario_form');
    }
 

     public function store(StoreVoluntarioRequest $request)
    {
        // 🚨 CORRIGIDO: Removida a validação redundante. O StoreVoluntarioRequest já lida com isso.
        // Apenas chamamos $request->validated() para obter o array de dados seguros.
        $validated = $request->validated();

        // Log dos dados recebidos
         Log::info('📥 Formulário de voluntariado recebido:', $validated);

          $areas = implode(', ', $validated['areas_colaborar']);
        $descricao_experiencia = $validated['descricao_experiencia'] ?? 'N/A';
        $outras_areas = $validated['outras_areas'] ?? 'N/A';

        $mensagem = "🤝 Novo pedido de voluntariado recebido:\n\n"
            . "👩‍💼 Nome completo: {$validated['nome_completo']}\n"
            . "🎂 Data de nascimento: {$validated['data_nascimento']}\n"
            . "📞 Telefone / WhatsApp: {$validated['telefone']}\n"
            . "📧 E-mail: {$validated['email']}\n"
            . "📍 Província / Cidade: {$validated['provincia']}\n"
            . "💼 Profissão: {$validated['profissao']}\n"
            . "🕒 Disponibilidade: {$validated['disponibilidade']}\n\n"
            . "💬 Motivação: {$validated['motivacao']}\n"
            . "🧠 Experiência prévia: {$validated['experiencia_previa']}\n"
            . "📖 Detalhes da experiência: {$descricao_experiencia}\n\n"
            . "📚 Áreas de colaboração: {$areas}\n"
            . "➕ Outras áreas: {$outras_areas}\n";

        // Email de destino (ambiente dev/prod)
        $toEmail = app()->environment('production')  
            ? 'voluntariado@sosmulherreal.com'
            : 'andrelubambi36@gmail.com';

        $resend = Resend::client(config('resend.api_key'));

        try {
            $result = $resend->emails->send([
                'from' => config('mail.from.address'),
                'to' => [$toEmail],
                'subject' => 'Nova Candidatura de Voluntariado - SOS Mulher Real',
                'text' => $mensagem,
            ]);

            Log::info('✅ Email de voluntariado enviado com sucesso! ID: ' . $result->id);

            return back()->with('success', 'Formulário enviado com sucesso! Entraremos em contacto em breve.');

        } catch (\Exception $e) {
            Log::error('❌ Erro ao enviar e-mail de voluntariado: ' . $e->getMessage());
            return back()->with('error', 'Erro ao enviar o formulário. Tente novamente.');
        }
    }
}
