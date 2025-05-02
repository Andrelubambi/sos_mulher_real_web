<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConsultaController;



Route::middleware('auth:sanctum')->get('/', function () {
    return view('index');  
})->name('index');

 
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

 
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

 
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
 
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


    // Páginas de erro ou utilitários protegidos
    Route::view('/video-player', 'video-player')->name('video-player');
    Route::view('/calendar', 'calendar')->name('calendar');
    Route::view('/chat', 'chat')->name('chat');
    Route::view('/profile', 'profile')->name('profile');

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
