@extends('layouts.auth')

@section('title', 'Login')

@section('content') 

    <div class="login-wrap">
        <div class="login-box bg-white box-shadow border-radius-10">
            <div class="login-title">
                <h2 class="text-center text-danger">Faça o seu login</h2>
            </div>
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success"> 
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            
            <form action="{{ url('login') }}" method="POST">
                @csrf
                <div class="login-card"> 
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fa fa-phone"></i>
                            </span>
                        </div>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               class="form-control" 
                               placeholder="Email" 
                               required>
                    </div>

                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="icon-copy dw dw-padlock1"></i>
                            </span> 
                        </div>
                        <input type="password" name="password" id="senha"
                               class="form-control" placeholder="Senha" required>
                        <div class="input-group-append">
                            <span class="input-group-text" id="toggleSenha" style="cursor: pointer;">
                                <i class="fa fa-eye"></i>
                            </span>
                        </div>
                    </div>

                    <p class="text-right">
                        <a href="{{ route('password.request') }}" class="text-danger font-weight-bold">
                            Esqueceu a senha?
                        </a>
                    </p>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="input-group mb-0">
                            <button type="submit" class="btn btn-danger btn-block">Entrar</button>
                        </div>
                    </div>
                </div>
                <p class="text-center mt-3">
    Ainda não tem uma conta?
    <a href="{{ route('choose.register') }}" class="text-danger font-weight-bold">
        Crie agora
    </a>
</p>

               <!-- <p class="text-center">Ainda não tem uma conta? <a href="{{ route('register') }}" class="text-danger">Crie agora</a></p>
                <p class="text-center">
                    Quer ser nosso parceiro?  
                    <a href="{{ route('parceria.form') }}" class="text-danger font-weight-bold">Clique aqui</a>
                </p> -->
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