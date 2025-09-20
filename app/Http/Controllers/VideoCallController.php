<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VideoCallController extends Controller
{
    /**
     * Gerar URL de videochamada para dois usuários
     */
    public function generateRoomUrl(Request $request, $userId)
    {
        $currentUserId = Auth::id();
         
        $minId = min($currentUserId, $userId);
        $maxId = max($currentUserId, $userId);
        $roomId = "sala-{$minId}-{$maxId}";
        
        $jitsiUrl = "http://jitsi.sosmulherreal.com/{$roomId}";
        
        return response()->json([
            'room_id' => $roomId,
            'room_url' => $jitsiUrl,
            'participants' => [$currentUserId, $userId]
        ]);
    }
}