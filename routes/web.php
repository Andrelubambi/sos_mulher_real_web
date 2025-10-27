<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConsultaController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\MensagemSosController;
use App\Http\Controllers\DoutorDashboardController;
use App\Http\Controllers\EstagiarioDashboardController;      
use App\Http\Controllers\VitimaDashboardController; 
use App\Http\Controllers\VideoCallController;  
use App\Http\Controllers\ParceriaController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\ProfileController;


Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::middleware('auth:sanctum')->get('/', function () {
    return redirect()->route('admin.dashboard');
})->name('index');

Route::middleware('auth:sanctum')->get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
 
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('login', [AuthController::class, 'login'])->name('login');

Route::view('/register', 'register')->name('register'); 
    //Parceiros
Route::get('/parceria', [ParceriaController::class, 'create'])->name('parceria.form');
Route::post('/parceria', [ParceriaController::class, 'store'])->name('parceria.enviar');


Route::get('password/forgot', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');



Route::post('/users/vitima/store', [UserController::class, 'storeVitima'])->name('users.vitima.store');

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/medicos', [MedicoController::class, 'index'])->name('medico.index');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::put('/users/{id}/password', [UserController::class, 'updatePassword'])->name('users.update_password');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/users/nao-doutores', [UserController::class, 'listNaoDoutores'])->name('users.nao_doutores');
    Route::get('/doutor', [UserController::class, 'createDoutor'])->name('users.doutor');
    Route::post('/users/doutor/store', [UserController::class, 'storeDoutor'])->name('users.doutor.store');
    Route::get('/estagiario', [UserController::class, 'createEstagiario'])->name('users.estagiario');
    Route::post('/users/estagiario/store', [UserController::class, 'storeEstagiario'])->name('users.estagiario.store');
    Route::get('/gerir-vitimas', [UserController::class, 'indexVitima'])->name('users.vitima');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update'); 
});

    // Consultas
    Route::get('/consultas', [ConsultaController::class, 'index'])->name('consulta');
    Route::post('/consultas', [ConsultaController::class, 'store'])->name('consulta.store');
    Route::delete('/consultas/{id}', [ConsultaController::class, 'destroy'])->name('consulta.destroy');
    Route::put('/consultas/{id}', [ConsultaController::class, 'update'])->name('consulta.update');
    Route::get('/consultas/{id}/edit', [ConsultaController::class, 'edit'])->name('consulta.edit');

    // chat
    Route::get('/chat', [ChatController::class, 'index'])->name('chat');
    Route::get('/chat/messages/{usuarioId}', [ChatController::class, 'getMessages'])->name('chat.getMessages');
    Route::post('/chat/send/{usuarioId}', [ChatController::class, 'sendMessage'])->name('chat.sendMessage');

    // VIDEO CALL ROUTES - ADD THESE LINES
    Route::get('/video-call/room/{userId}', [VideoCallController::class, 'generateRoomUrl'])
        ->name('video.call.room');
    
    Route::post('/video-call/end', [VideoCallController::class, 'endCall'])
        ->name('video.call.end');
    
    Route::get('/video-call/status', [VideoCallController::class, 'checkJitsiStatus'])
        ->name('video.call.status');

    // Grupos
    Route::get('/grupos', [GrupoController::class, 'index'])->name('grupos.index');
    Route::get('/grupos/criar', [GrupoController::class, 'create'])->name('grupos.create');
    Route::post('/grupos', [GrupoController::class, 'store'])->name('grupos.store');
    Route::post('/grupos/{grupo}/mensagens', [GrupoController::class, 'sendMessage'])->name('grupos.mensagens.send');
    Route::post('/grupos/{grupo}/entrar', [GrupoController::class, 'entrar'])->name('grupos.entrar');
    Route::post('/grupos/{grupo}/sair', [GrupoController::class, 'sair'])->name('grupos.sair');
    Route::post('/grupos/{grupo}/adicionar-membros', [GrupoController::class, 'adicionarMembros'])->name('grupos.adicionarMembros');
Route::post('/grupos/{grupo}/remover-usuario/{user}', [GrupoController::class, 'removerUsuario'])->name('grupos.removerUsuario');
    Route::post('/grupos', [GrupoController::class, 'store'])->name('grupos.store');
    Route::delete('/grupos/{grupo}', [GrupoController::class, 'destroy'])->name('grupos.destroy');
    Route::get('/grupos', [GrupoController::class, 'index'])->name('grupos.index');
    Route::get('/grupos/{grupo}', [GrupoController::class, 'show'])->name('grupos.show');
    Route::get('/grupos/{grupo}/mensagens', [GrupoController::class, 'getMensagens'])->name('grupos.mensagens');

    Route::view('/profile', 'profile')->name('profile'); 

    // ROTA POST - para enviar mensagem
    Route::post('/mensagem_sos',[MensagemSosController::class,'enviarMensagemSos'])->name('mensagem_sos.send');
    Route::post('/mensagem_lida',[MensagemSosController::class,'mensagemLida'])->name('mensagem_lida');
    Route::get('/mensagens_nao_lidas',[MensagemSosController::class,'pegarMensagensNaoLidas'])->name('mensagens_nao_lidas');
    Route::get('/responder_mensagem_sos/{id}', [ChatController::class, 'responderMensagemSos'])->name('responder_mensagem_sos');


        Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');



    // Rotas de dashboard, protegidas pelo middleware de role
    Route::middleware(['auth', 'role:admin'])->get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::middleware(['auth', 'role:doutor'])->get('/doutor/dashboard', [DoutorDashboardController::class, 'index'])->name('doutor.dashboard');
    Route::middleware(['auth', 'role:estagiario'])->get('/estagiario/dashboard', [EstagiarioDashboardController::class, 'index'])->name('estagiario.dashboard');
    Route::middleware(['auth', 'role:vitima'])->get('/vitima/dashboard', [VitimaDashboardController::class, 'index'])->name('vitima.dashboard');
 
  