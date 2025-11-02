@extends('layouts.auth')

@section('title', 'Criar Conta')

@section('content')
<div class="register-choice-container d-flex flex-column align-items-center justify-content-center min-vh-100 bg-light">
    <div class="text-center mb-4">
        <h2 class="text-danger font-weight-bold">Escolha como deseja se cadastrar</h2>
        <p class="text-muted">Selecione o tipo de conta que deseja criar para continuar</p>
    </div>

    <div class="d-flex flex-wrap justify-content-center gap-4">
        <!-- Card Usuário -->
        <a href="{{ route('register') }}" class="card text-center border-0 shadow-sm p-4 rounded-4" 
           style="width: 260px; text-decoration:none;">
            <div class="mb-3">
                <i class="fa fa-user-circle fa-3x text-danger"></i>
            </div>
            <h5 class="text-dark mb-2">Usuário Comum</h5>
            <p class="text-muted small">Ideal para quem quer acessar e usar o sistema normalmente.</p>
        </a>

        <!-- Card Parceiro -->
        <a href="{{ route('parceria.form') }}" class="card text-center border-0 shadow-sm p-4 rounded-4" 
           style="width: 260px; text-decoration:none;">
            <div class="mb-3">
                <i class="fa fa-handshake-o fa-3x text-danger"></i>
            </div>
            <h5 class="text-dark mb-2">Parceiro</h5>
            <p class="text-muted small">Cadastre-se como parceiro e colabore oficialmente conosco.</p>
        </a>

        <!-- Card Voluntário -->
        <a href="{{ route('voluntario.form') }}" class="card text-center border-0 shadow-sm p-4 rounded-4" 
           style="width: 260px; text-decoration:none;">
            <div class="mb-3">
                <i class="fa fa-heart fa-3x text-danger"></i>
            </div>
            <h5 class="text-dark mb-2">Voluntário</h5>
            <p class="text-muted small">Junte-se à nossa causa como voluntário e faça a diferença.</p>
        </a>
    </div>

    <div class="mt-4">
        <a href="{{ route('login') }}" class="text-danger font-weight-bold">
            <i class="fa fa-arrow-left"></i> Voltar ao login
        </a>
    </div>
</div>
@endsection
