<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class VideoCallController extends Controller
{
    /**
     * Gerar URL de videochamada para dois usuários
     */
    public function generateRoomUrl(Request $request, $userId)
    {
        try {
            $currentUserId = Auth::id();
            
            // Validar se o usuário de destino existe
            $targetUser = User::find($userId);
            if (!$targetUser) {
                return response()->json([
                    'success' => false,
                    'error' => 'Usuário não encontrado'
                ], 404);
            }
            
            // Verificar se não está tentando chamar a si mesmo
            if ($currentUserId == $userId) {
                return response()->json([
                    'success' => false,
                    'error' => 'Não é possível iniciar videochamada consigo mesmo'
                ], 400);
            }
            
            // Gerar ID da sala baseado nos IDs dos usuários (sempre na mesma ordem)
            $minId = min($currentUserId, $userId);
            $maxId = max($currentUserId, $userId);
            $roomId = "sosmulher-{$minId}-{$maxId}";
            
            // URL do seu servidor Jitsi
            $jitsiBaseUrl = config('app.jitsi_url', 'http://jitsi.sosmulherreal.com');
            
            // Configurações do Jitsi
            $jitsiParams = [
                'config.prejoinPageEnabled' => 'false',
                'config.startWithAudioMuted' => 'false',
                'config.startWithVideoMuted' => 'false',
                'config.enableWelcomePage' => 'false',
                'config.enableClosePage' => 'false',
                'config.enableLobbyMode' => 'false',
                'config.disableDeepLinking' => 'true',
                'userInfo.displayName' => urlencode(Auth::user()->name),
                'config.toolbarButtons' => json_encode([
                    'microphone', 'camera', 'desktop', 'fullscreen',
                    'fodeviceselection', 'hangup', 'profile', 'info',
                    'recording', 'livestreaming', 'etherpad', 'sharedvideo',
                    'settings', 'raisehand', 'videoquality', 'filmstrip',
                    'invite', 'feedback', 'stats', 'shortcuts', 'tileview'
                ])
            ];
            
            $configString = http_build_query($jitsiParams, '', '&');
            $fullJitsiUrl = "{$jitsiBaseUrl}/{$roomId}?{$configString}";
            
            // Log da atividade (opcional)
            Log::info('Videochamada iniciada', [
                'caller_id' => $currentUserId,
                'caller_name' => Auth::user()->name,
                'target_id' => $userId,
                'target_name' => $targetUser->name,
                'room_id' => $roomId,
                'timestamp' => now()
            ]);
            
            return response()->json([
                'success' => true,
                'room_id' => $roomId,
                'room_url' => $fullJitsiUrl,
                'participants' => [
                    'caller' => [
                        'id' => $currentUserId,
                        'name' => Auth::user()->name
                    ],
                    'target' => [
                        'id' => $userId,
                        'name' => $targetUser->name
                    ]
                ],
                'jitsi_server' => $jitsiBaseUrl,
                'created_at' => now()->toISOString()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erro ao gerar URL de videochamada', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'caller_id' => Auth::id() ?? 'N/A',
                'target_id' => $userId ?? 'N/A'
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Erro interno do servidor ao iniciar videochamada'
            ], 500);
        }
    }
    
    /**
     * Registrar fim da videochamada (opcional)
     */
    public function endCall(Request $request)
    {
        try {
            $request->validate([
                'room_id' => 'required|string',
                'duration' => 'nullable|integer'
            ]);
            
            Log::info('Videochamada encerrada', [
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name,
                'room_id' => $request->room_id,
                'duration' => $request->duration,
                'timestamp' => now()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Chamada encerrada com sucesso'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erro ao encerrar videochamada', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'room_id' => $request->room_id ?? 'N/A'
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Erro ao encerrar chamada'
            ], 500);
        }
    }
    
    /**
     * Verificar disponibilidade do servidor Jitsi
     */
    public function checkJitsiStatus()
    {
        $jitsiBaseUrl = config('app.jitsi_url', 'http://jitsi.sosmulherreal.com');
        
        try {
            $context = stream_context_create([
                'http' => [
                    'timeout' => 10,
                    'method' => 'GET',
                    'ignore_errors' => true
                ]
            ]);
            
            $response = @file_get_contents($jitsiBaseUrl, false, $context);
            $isOnline = $response !== false;
            
            return response()->json([
                'success' => true,
                'jitsi_url' => $jitsiBaseUrl,
                'status' => $isOnline ? 'online' : 'offline',
                'response_received' => $isOnline,
                'checked_at' => now()->toISOString()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erro ao verificar status do Jitsi', [
                'error' => $e->getMessage(),
                'jitsi_url' => $jitsiBaseUrl
            ]);
            
            return response()->json([
                'success' => false,
                'jitsi_url' => $jitsiBaseUrl,
                'status' => 'error',
                'error' => $e->getMessage(),
                'checked_at' => now()->toISOString()
            ], 500);
        }
    }
    
    /**
     * Obter histórico de chamadas do usuário (opcional)
     */
    public function getCallHistory()
    {
        try {
            // Aqui você pode implementar lógica para buscar histórico
            // Por exemplo, se você tiver uma tabela de logs de chamadas
            
            return response()->json([
                'success' => true,
                'calls' => [], // Implementar quando necessário
                'message' => 'Histórico não implementado ainda'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Erro ao buscar histórico'
            ], 500);
        }
    }
}