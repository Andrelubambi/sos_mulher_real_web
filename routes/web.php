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



/*/ ROTA GET - para visualizar (se necessário)
Route::get('/mensagem_sos',function(){
    return redirect()->back();
})->name('mensagem_sos.view');  // NOME ALTERADO
*/

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






// === ROTAS DE DEBUG (REMOVER DEPOIS) ===
Route::get('/debug-db', function() {
    try {
        \DB::connection()->getPdo();
        return response()->json([
            'status' => '✅ DB CONECTADO',
            'database' => \DB::connection()->getDatabaseName(),
            'tables' => \DB::select('SHOW TABLES'),
            'app_key' => config('app.key'),
            'app_env' => config('app.env'),
            'db_host' => env('DB_HOST'),
            'db_name' => env('DB_NAME')
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => '❌ ERRO DB',
            'error' => $e->getMessage(),
            'db_host' => env('DB_HOST'),
            'db_database' => env('DB_NAME'),
            'app_key' => config('app.key')
        ], 500);
    }
});

Route::get('/debug-storage', function() {
    return response()->json([
        'storage_writable' => is_writable(storage_path()),
        'bootstrap_writable' => is_writable(base_path('bootstrap/cache')),
        'app_key' => config('app.key') ? '✅ CONFIGURADA' : '❌ FALTANDO'
    ]);
});

Route::get('/debug-env', function() {
    return response()->json([
        'app_env' => config('app.env'),
        'app_debug' => config('app.debug'),
        'app_url' => config('app.url'),
        'db_connection' => config('database.default')
    ]);
});


Route::get('/debug-mysql-vars', function() {
    return response()->json([
        'railway_mysql_host' => env('RAILWAY_MYSQL_HOST'),
        'railway_mysql_port' => env('RAILWAY_MYSQL_PORT'),
        'railway_mysql_user' => env('RAILWAY_MYSQL_USER'),
        'railway_mysql_password' => env('RAILWAY_MYSQL_PASSWORD'),
        'railway_mysql_database' => env('RAILWAY_MYSQL_DATABASE'),
        'db_host' => env('DB_HOST'),
        'db_port' => env('DB_PORT'),
        'db_user' => env('DB_USER'),
        'db_password' => env('DB_PASSWORD'),
        'db_name' => env('DB_NAME')
    ]);
});

Route::get('/test-mysql-connection', function() {
    $hosts_to_test = [
        'mysql.railway.internal',
        '127.0.0.1',
        'localhost'
    ];
    
    $results = [];
    
    foreach ($hosts_to_test as $host) {
        try {
            config(['database.connections.mysql.host' => $host]);
            \DB::connection()->getPdo();
            $results[$host] = '✅ CONECTADO';
        } catch (\Exception $e) {
            $results[$host] = '❌ ERRO: ' . $e->getMessage();
        }
    }
    
    return response()->json($results);
});


Route::get('/debug-current-db', function() {
    return response()->json([
        'current_db_host' => config('database.connections.mysql.host'),
        'current_db_port' => config('database.connections.mysql.port'),
        'current_db_database' => config('database.connections.mysql.database'),
        'current_db_username' => config('database.connections.mysql.username'),
        'env_db_host' => env('DB_HOST'),
        'env_db_port' => env('DB_PORT')
    ]);
});


Route::get('/debug-password', function() {
    return response()->json([
        'db_password_set' => !empty(env('DB_PASSWORD')),
        'db_password_length' => strlen(env('DB_PASSWORD') ?? ''),
        'db_username' => env('DB_USERNAME'),
        'db_host' => env('DB_HOST')
    ]);
});


Route::get('/debug-all-env', function() {
    $allEnv = getenv();
    $relevantVars = [];
    
    foreach ($allEnv as $key => $value) {
        if (strpos($key, 'MYSQL') !== false || strpos($key, 'DB') !== false || strpos($key, 'RAILWAY') !== false) {
            $relevantVars[$key] = $value;
        }
    }
    
    return response()->json($relevantVars);
});

Route::get('/check-connection', function() {
    try {
        \DB::connection()->getPdo();
        return response()->json([
            'status' => '✅ CONECTADO',
            'host' => config('database.connections.mysql.host'),
            'port' => config('database.connections.mysql.port'),
            'database' => config('database.connections.mysql.database'),
            'username' => config('database.connections.mysql.username')
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => '❌ ERRO',
            'error' => $e->getMessage(),
            'config' => [
                'host' => config('database.connections.mysql.host'),
                'port' => config('database.connections.mysql.port'),
                'database' => config('database.connections.mysql.database'),
                'username' => config('database.connections.mysql.username')
            ]
        ], 500);
    }
});



Route::get('/health-check', function() {
    try {
        \DB::connection()->getPdo();
        
        return response()->json([
            'status' => 'healthy',
            'database' => '✅ connected',
            'storage_writable' => is_writable(storage_path()),
            'cache_writable' => is_writable(base_path('bootstrap/cache')),
            'app_key' => config('app.key') ? '✅ set' : '❌ missing',
            'environment' => config('app.env')
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'database_error' => $e->getMessage(),
            'app_key' => config('app.key')
        ], 500);
    }
});

Route::get('/check-env', function() {
    return response()->json([
        'app_name' => env('APP_NAME'),
        'app_env' => env('APP_ENV'),
        'app_key_set' => !empty(env('APP_KEY')),
        'app_url' => env('APP_URL'),
        'app_debug' => env('APP_DEBUG'),
        'db_host' => env('DB_HOST'),
        'db_port' => env('DB_PORT'),
        'all_env' => collect($_ENV)->filter(function($value, $key) {
            return in_array($key, ['APP_NAME', 'APP_ENV', 'APP_KEY', 'APP_DEBUG', 'APP_URL', 'DB_CONNECTION', 'DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME']);
        })
    ]);
});


Route::post('/debug-form', function(Request $request) {
    return response()->json([
        'received_data' => $request->all(),
        'csrf_token' => csrf_token(),
        'session_id' => session()->getId()
    ]);
});