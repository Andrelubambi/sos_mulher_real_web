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
  

/*Route::middleware('auth:web')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});*/

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::middleware('auth:sanctum')->get('/', function () {
    return redirect()->route('admin.dashboard');
})->name('index');


Route::middleware('auth:sanctum')->get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');


 
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('login', [AuthController::class, 'login'])->name('login');



 
Route::view('/register', 'register')->name('register');
Route::get('/vitima', [UserController::class, 'createVitima'])->name('users.vitima');
Route::post('/users/vitima/store', [UserController::class, 'storeVitima'])->name('users.vitima.store');

 
Route::view('/faq', 'faq')->name('faq');
Route::view('/blog', 'blog')->name('blog');
Route::view('/blog-detail', 'blog-detail')->name('blog-detail');
Route::view('/gallery', 'gallery')->name('gallery');

Route::middleware('auth:sanctum')->group(function () {

 
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');


Route::get('/users/nao-doutores', [UserController::class, 'listNaoDoutores'])->name('users.nao_doutores');

 
    Route::get('/doutor', [UserController::class, 'createDoutor'])->name('users.doutor');
    Route::post('/users/doutor/store', [UserController::class, 'storeDoutor'])->name('users.doutor.store');

    Route::get('/estagiario', [UserController::class, 'createEstagiario'])->name('users.estagiario');
    Route::post('/users/estagiario/store', [UserController::class, 'storeEstagiario'])->name('users.estagiario.store');



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

  // Grupos
   Route::get('/grupos', [GrupoController::class, 'index'])->name('grupos.index');
   Route::get('/grupos/criar', [GrupoController::class, 'create'])->name('grupos.create');
   Route::post('/grupos', [GrupoController::class, 'store'])->name('grupos.store');
   Route::post('/grupos/{grupo}/mensagens', [GrupoController::class, 'sendMessage'])->name('grupos.mensagens.send');
   Route::post('/grupos/{grupo}/entrar', [GrupoController::class, 'entrar'])->name('grupos.entrar');
   Route::post('/grupos/{grupo}/sair', [GrupoController::class, 'sair'])->name('grupos.sair');
   Route::delete('/grupos/{grupo}/remover/{user}', [GrupoController::class, 'removerUsuario'])->name('grupos.removerUsuario');
   Route::post('/grupos', [GrupoController::class, 'store'])->name('grupos.store');
   Route::delete('/grupos/{grupo}', [GrupoController::class, 'destroy'])->name('grupos.destroy');
   Route::get('/grupos', [GrupoController::class, 'index'])->name('grupos.index');
   Route::get('/grupos/{grupo}', [GrupoController::class, 'show'])->name('grupos.show');
   Route::get('/grupos/{grupo}/mensagens', [GrupoController::class, 'getMensagens'])->name('grupos.mensagens');
   
 


    Route::view('/profile', 'profile')->name('profile'); 



// ROTA GET - para visualizar (se necessário)
Route::get('/mensagem_sos',function(){
    return redirect()->back();
})->name('mensagem_sos.view');  // NOME ALTERADO

// ROTA POST - para enviar mensagem
Route::post('/mensagem_sos',[MensagemSosController::class,'enviarMensagemSos'])->name('mensagem_sos.send'); // NOME ALTERADO
    Route::post('/mensagem_lida',[MensagemSosController::class,'mensagemLida'])->name('mensagem_lida');
    Route::get('/mensagens_nao_lidas',[MensagemSosController::class,'pegarMensagensNaoLidas'])->name('mensagens_nao_lidas');
    Route::get('/responder_mensagem_sos/{id}', [ChatController::class, 'responderMensagemSos'])->name('responder_mensagem_sos');


// Rotas para as novas páginas
Route::get('/nao-suicidio', function () {
    return view('nao_suicidio');
})->name('nao_suicidio');

Route::get('/testemunhos', function () {
    return view('testemunhos');
})->name('testemunhos');


    // Rotas de dashboard, protegidas pelo middleware de role
Route::middleware(['auth', 'role:admin'])->get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
Route::middleware(['auth', 'role:doutor'])->get('/doutor/dashboard', [DoutorDashboardController::class, 'index'])->name('doutor.dashboard');
Route::middleware(['auth', 'role:estagiario'])->get('/estagiario/dashboard', [EstagiarioDashboardController::class, 'index'])->name('estagiario.dashboard');
Route::middleware(['auth', 'role:vitima'])->get('/vitima/dashboard', [VitimaDashboardController::class, 'index'])->name('vitima.dashboard');



    // Demais páginas administrativas
    Route::view('/index2', 'index2')->name('index2');
    Route::view('/index3', 'index3')->name('index3');

    // Páginas de erro protegidas
    Route::view('/400', '400')->name('400');
    Route::view('/403', '403')->name('403');
    Route::view('/404', '404')->name('404');
    Route::view('/500', '500')->name('500');
    Route::view('/503', '503')->name('503');

});


Route::get('/teste',function(){
    return view('teste-websocket');
});

Route::get('/fire',function(){
    event(new App\Events\TestEvent());
    return 'okkkkkk';
});

Route::get('/send', function () {
    event(new \App\Events\SendMessage());
    dd('Event Run Successfully.');
});

