@extends('layouts.auth')

@section('title', 'Nova Senha')

@section('content')

    <div class="login-wrap">
        <div class="login-box bg-white box-shadow border-radius-10">
            <div class="login-title">
                <h2 class="text-center text-danger">Definir Nova Senha</h2>
                <p class="text-center text-muted mt-2">Crie uma nova senha para a sua conta.</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                    </div>
                    <input type="email" name="email" class="form-control" placeholder="Seu e-mail" required>
                </div>

                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="icon-copy dw dw-padlock1"></i></span>
                    </div>
                    <input type="password" name="password" id="senha" class="form-control" placeholder="Nova senha" required>
                    <div class="input-group-append">
                        <span class="input-group-text" id="toggleSenha" style="cursor: pointer;">
                            <i class="fa fa-eye"></i>
                        </span>
                    </div>
                </div> 

                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="icon-copy dw dw-padlock1"></i></span>
                    </div>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Confirmar nova senha" required>
                </div>

                <div class="input-group mb-0">
                    <button type="submit" class="btn btn-danger btn-block">Salvar nova senha</button>
                </div>

                <p class="text-center mt-3">
                    <a href="{{ route('login') }}" class="text-danger font-weight-bold">Voltar ao login</a>
                </p>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        (function(){
            var senha = document.getElementById('senha');
            var btn = document.getElementById('toggleSenha');
            if (btn && senha){
                btn.addEventListener('click', function(){
                    var isPwd = senha.getAttribute('type') === 'password';
                    senha.setAttribute('type', isPwd ? 'text' : 'password');
                    this.innerHTML = isPwd ? '<i class="fa fa-eye-slash"></i>' : '<i class="fa fa-eye"></i>';
                });
            }
        })();
    </script>
@endsection