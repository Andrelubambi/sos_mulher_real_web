<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\UserController;


Route::view('/', 'index')->name('index');


Route::get('/vitima', [UserController::class, 'createVitima'])->name('users.vitima');
Route::get('/estagiario', [UserController::class, 'createAssistente'])->name('users.assistente');

Route::get('/doutor', [UserController::class, 'createDoutor'])->name('users.doutor');
Route::post('/users/doutor/store', [UserController::class, 'storeDoutor'])->name('users.doutor.store');
Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

Route::view('/400', '400')->name('400');
Route::view('/403', '403')->name('403');
Route::view('/404', '404')->name('404');
Route::view('/500', '500')->name('500');
Route::view('/503', '503')->name('503'); 

Route::view('/advanced-components', 'advanced-components')->name('advanced-components');
Route::view('/apexcharts', 'apexcharts')->name('apexcharts');
Route::view('/basic-table', 'basic-table')->name('basic-table');
Route::view('/blank', 'blank')->name('blank');
Route::view('/blog-detail', 'blog-detail')->name('blog-detail');
Route::view('/blog', 'blog')->name('blog');
Route::view('/bootstrap-icon', 'bootstrap-icon')->name('bootstrap-icon');
Route::view('/calendar', 'calendar')->name('calendar');
Route::view('/chat', 'chat')->name('chat');
Route::view('/color-settings', 'color-settings')->name('color-settings');
Route::view('/contact-directory', 'contact-directory')->name('contact-directory');
Route::view('/custom-icon', 'custom-icon')->name('custom-icon');
Route::view('/datatable', 'datatable')->name('datatable');
Route::view('/faq', 'faq')->name('faq');
Route::view('/font-awesome', 'font-awesome')->name('font-awesome');
Route::view('/forgot-password', 'forgot-password')->name('forgot-password');
Route::view('/form-basic', 'form-basic')->name('form-basic');
Route::view('/form-pickers', 'form-pickers')->name('form-pickers');
Route::view('/form-wizard', 'form-wizard')->name('form-wizard');
Route::view('/foundation', 'foundation')->name('foundation');
Route::view('/gallery', 'gallery')->name('gallery');
Route::view('/gettting-started', 'gettting-started')->name('gettting-started');
Route::view('/highchart', 'highchart')->name('highchart');
Route::view('/html5-editor', 'html5-editor')->name('html5-editor');
Route::view('/image-croper', 'image-croper')->name('image-croper');
Route::view('/image', 'image')->name('image');
Route::view('/image-dropzone', 'image-dropzone')->name('image-dropzone');
Route::view('/index2', 'index2')->name('index2');
Route::view('/index3', 'index3')->name('index3');


Route::view('/vitima', 'vitima')->name('vitima');
Route::view('/consulta', 'consulta')->name('consulta');
Route::view('/assistente', 'assistente')->name('assistente');
Route::view('/introduction', 'introduction')->name('introduction');
Route::view('/invoice', 'invoice')->name('invoice');
Route::view('/ionicons', 'ionicons')->name('ionicons');
Route::view('/jvectormap', 'jvectormap')->name('jvectormap');
Route::view('/knob-chart', 'knob-chart')->name('knob-chart');
Route::view('/login', 'login')->name('login');
Route::view('/pricing-table', 'pricing-table')->name('pricing-table');
Route::view('/product-detail', 'product-detail')->name('product-detail');
Route::view('/product', 'product')->name('product');
Route::view('/profile', 'profile')->name('profile');
Route::view('/register', 'register')->name('register');
Route::view('/reset-password', 'reset-password')->name('reset-password');
Route::view('/sitemap', 'sitemap')->name('sitemap');
Route::view('/themify', 'themify')->name('themify');
Route::view('/third-party-plugins', 'third-party-plugins')->name('third-party-plugins');
Route::view('/ui-buttons', 'ui-buttons')->name('ui-buttons');
Route::view('/ui-cards-hover', 'ui-cards-hover')->name('ui-cards-hover');
Route::view('/ui-cards', 'ui-cards')->name('ui-cards');
Route::view('/ui-carousel', 'ui-carousel')->name('ui-carousel');
Route::view('/ui-list-group', 'ui-list-group')->name('ui-list-group');
Route::view('/ui-modals', 'ui-modals')->name('ui-modals');
Route::view('/ui-notification', 'ui-notification')->name('ui-notification');
Route::view('/ui-progressbar', 'ui-progressbar')->name('ui-progressbar');
Route::view('/ui-range-slider', 'ui-range-slider')->name('ui-range-slider');
Route::view('/ui-sweet-alert', 'ui-sweet-alert')->name('ui-sweet-alert');
Route::view('/ui-tabs', 'ui-tabs')->name('ui-tabs');
Route::view('/ui-timeline', 'ui-timeline')->name('ui-timeline');
Route::view('/ui-tooltip-popover', 'ui-tooltip-popover')->name('ui-tooltip-popover');
Route::view('/ui-typography', 'ui-typography')->name('ui-typography');
Route::view('/video-player', 'video-player')->name('video-player');

