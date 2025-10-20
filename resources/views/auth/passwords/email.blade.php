<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Redefinir Senha | SOS-MULHER</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link rel="icon" type="image/png" href="/vendors/images/android-chrome-192x192.png" />
    <link rel="stylesheet" type="text/css" href="/vendors/styles/core.css" />
    <link rel="stylesheet" type="text/css" href="/vendors/styles/icon-font.min.css" />
    <link rel="stylesheet" type="text/css" href="/vendors/styles/style.css" />

    <style>
        /*
         * Define a altura mínima do corpo como 100% da viewport
         * e configura o display como flex para usar as propriedades de centralização.
         * A propriedade `margin: 0` garante que não haverá margem extra no corpo,
         * o que poderia quebrar a centralização de altura total.
         */
        body.login-page {
            min-height: 100vh; /* 100% da altura da viewport */
            display: flex;
            flex-direction: column; /* Organiza os itens em coluna */
            margin: 0; /* Remove margem padrão */
        }

        /*
         * O `login-wrap` deve ocupar o espaço restante (flex-grow: 1)
         * e também usar flexbox para centralizar seu conteúdo (`login-box`).
         * `align-items: center` centraliza verticalmente.
         * `justify-content: center` centraliza horizontalmente.
         */
        .login-wrap {
            flex-grow: 1; /* Ocupa todo o espaço vertical disponível */
            display: flex;
            align-items: center; /* Centraliza verticalmente o login-box */
            justify-content: center; /* Centraliza horizontalmente o login-box */
            padding: 20px; /* Adiciona um padding para telas menores */
        }

        /*
         * É bom definir uma largura máxima para o formulário, caso ele não tenha.
         * Se `core.css` ou `style.css` já define uma largura, este pode ser ignorado.
         */
        .login-box {
            max-width: 450px; /* Exemplo de largura máxima */
            width: 100%; /* Garante que ele use a largura total até o max-width */
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
    </body>
</html>