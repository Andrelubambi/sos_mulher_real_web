<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Nova Senha | SOS-MULHER</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link rel="icon" type="image/png" href="/vendors/images/android-chrome-192x192.png" />
    <link rel="stylesheet" type="text/css" href="/vendors/styles/core.css" />
    <link rel="stylesheet" type="text/css" href="/vendors/styles/icon-font.min.css" />
    <link rel="stylesheet" type="text/css" href="/vendors/styles/style.css" />

    <style>
        /*
         * 1. Define a altura mínima do corpo como 100% da viewport e configura o Flexbox.
         */
        body.login-page {
            min-height: 100vh; /* 100% da altura da viewport */
            display: flex;
            flex-direction: column; /* Organiza o header e o formulário em coluna */
            margin: 0; /* Remove margem padrão */
        }

        /*
         * 2. Faz o login-wrap ocupar o espaço restante e usa Flexbox para centralizar seu conteúdo.
         */
        .login-wrap {
            flex-grow: 1; /* Ocupa todo o espaço vertical disponível */
            display: flex;
            align-items: center; /* Centraliza verticalmente o login-box */
            justify-content: center; /* Centraliza horizontalmente o login-box */
            padding: 20px; /* Adiciona padding para telas menores */
        }

        /*
         * 3. Garante que o box do formulário se ajuste a uma largura máxima.
         */
        .login-box {
            max-width: 450px; /* Exemplo de largura máxima */
            width: 100%;
        }
    </style>
</head>

<body class="login-page custom-background">
    <div class="login-header box-shadow">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div class="brand-logo">
                <a href="{{ route('login') }}">
                    <img src="/vendors/images/android-chrome-192x192.png" alt="" style="height: 60px;" />
                </a>
            </div>
        </div>
    </div>

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
</body>
</html>