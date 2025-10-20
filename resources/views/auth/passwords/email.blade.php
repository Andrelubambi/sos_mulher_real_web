@extends('layouts.auth')

@section('title', 'Redefinir Senha')

@section('content')

    <div class="login-wrap">
        <div class="login-box bg-white box-shadow border-radius-10">
            
            <div class="login-title">
                <h2 class="text-center text-danger">Recuperar Senha</h2>
                <p class="text-center text-muted mt-2">Digite o seu e-mail e enviaremos um link para redefinição.</p>
            </div>

            @if (session('status'))
                <div class="alert alert-success text-center">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fa fa-envelope"></i>
                        </span>
                    </div>
                    <input type="email" name="email" class="form-control" placeholder="Seu e-mail" required>
                </div>

                <div class="input-group mb-0">
                    <button type="submit" class="btn btn-danger btn-block">Enviar link</button>
                </div>

                <p class="text-center mt-3">
                    <a href="{{ route('login') }}" class="text-danger font-weight-bold">Voltar ao login</a>
                </p>
            </form>
        </div>
    </div>
@endsection