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
                    'error' => 'Usuário não encontrado'
                ], 404);
            }
            
            // Verificar se não está tentando chamar a si mesmo
            if ($currentUserId == $userId) {
                return response()->json([
                    'error' => 'Não é possível iniciar videochamada consigo mesmo'
                ], 400);
            }
            
            // Gerar ID da sala baseado nos IDs dos usuários (sempre na mesma ordem)
            $minId = min($currentUserId, $userId);
            $maxId = max($currentUserId, $userId);
            $roomId = "sala-{$minId}-{$maxId}";
            
            // URL do seu servidor Jitsi
            $jitsiBaseUrl = config('app.jitsi_url', 'http://jitsi.sosmulherreal.com');
            $jitsiUrl = "{$jitsiBaseUrl}/{$roomId}";
            
            // Configurações opcionais do Jitsi
            $jitsiConfig = [
                'config.prejoinPageEnabled' => 'false',
                'config.startWithAudioMuted' => 'false',
                'config.startWithVideoMuted' => 'false',
                'config.enableWelcomePage' => 'false',
                'config.enableClosePage' => 'false',
                'userInfo.displayName' => Auth::user()->name
            ];
            
            $configString = http_build_query($jitsiConfig, '', '&');
            $fullJitsiUrl = "{$jitsiUrl}?{$configString}";
            
            // Log da atividade (opcional)
            Log::info('Videochamada iniciada', [
                'caller_id' => $currentUserId,
                'target_id' => $userId,
                'room_id' => $roomId
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
                'created_at' => now()->toISOString()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erro ao gerar URL de videochamada', [
                'error' => $e->getMessage(),
                'caller_id' => Auth::id(),
                'target_id' => $userId
            ]);
            
            return response()->json([
                'error' => 'Erro interno do servidor ao iniciar videochamada'
            ], 500);
        }
    }
    
    /**
     * Registrar fim da videochamada (opcional)
     */
    public function endCall(Request $request)
    {
        $request->validate([
            'room_id' => 'required|string',
            'duration' => 'nullable|integer'
        ]);
        
        Log::info('Videochamada encerrada', [
            'user_id' => Auth::id(),
            'room_id' => $request->room_id,
            'duration' => $request->duration
        ]);
        
        return response()->json(['success' => true]);
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
                    'timeout' => 5,
                    'method' => 'GET'
                ]
            ]);
            
            $response = @file_get_contents($jitsiBaseUrl, false, $context);
            $isOnline = $response !== false;
            
            return response()->json([
                'jitsi_url' => $jitsiBaseUrl,
                'status' => $isOnline ? 'online' : 'offline',
                'checked_at' => now()->toISOString()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'jitsi_url' => $jitsiBaseUrl,
                'status' => 'error',
                'error' => $e->getMessage(),
                'checked_at' => now()->toISOString()
            ], 500);
        }
    }
}