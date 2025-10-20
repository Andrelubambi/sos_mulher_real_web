<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>@yield('title', 'Autenticação') | SOS-MULHER</title> 
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    
    <link rel="icon" type="image/png" href="/vendors/images/android-chrome-192x192.png" />
    <link rel="stylesheet" type="text/css" href="/vendors/styles/core.css" />
    <link rel="stylesheet" type="text/css" href="/vendors/styles/icon-font.min.css" />
    <link rel="stylesheet" type="text/css" href="/vendors/styles/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <style>
        /* Efeito de desfoque no fundo */
        body.login-page {
            position: relative;
            min-height: 100vh;
            display: flex;
            flex-direction: column; 
            margin: 0; 
            padding: 0;
        }

        body.login-page::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('/vendors/images/background-image.png');
            background-size: cover;
            background-position: center;
            filter: blur(8px);
            transform: scale(1.05);
            z-index: -1;
        }

        /* Garantir que o conteúdo fique acima do fundo desfocado */
        .login-header,
        .login-wrap {
            position: relative;
            z-index: 1;
        }

        /* Container Wrapper */
        .login-wrap {
            flex-grow: 1; 
            display: flex;
            justify-content: center; 
            align-items: center;
            padding: 30px 20px;
        }

        /* A caixa do formulário */
        .login-box {
            max-width: 450px; 
            width: 90%;
            margin: 0 auto;
            padding: 40px !important; 
            min-width: 300px; 
            background: rgba(255, 255, 255, 0.95); /* Fundo levemente transparente */
            backdrop-filter: blur(5px); /* Desfoque adicional na caixa */
            border-radius: 10px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        /* Estilos de Acessibilidade e Tamanho */
        .login-title {
            margin-bottom: 35px !important;
        }

        h2 {
            font-size: 2rem !important;
        }

        .input-group {
            margin-bottom: 20px !important;
        }

        .form-control {
            padding: 16px 20px;
            font-size: 1.1rem;
            height: auto;
        }

        .input-group-text {
            padding: 16px 20px;
            font-size: 1.2rem;
        }

        .btn-block {
            padding: 16px;
            font-size: 1.2rem;
            font-weight: bold;
        }

        p {
            font-size: 1.1rem;
            margin: 15px 0 !important;
        }

        a {
            font-size: 1.1rem;
            font-weight: bold;
        }

        /* Ajuste Responsivo Básico */
        @media (max-width: 768px) {
            .login-box {
                min-width: unset;
                width: 95%;
                padding: 30px 20px !important;
            }
            
            body.login-page::before {
                filter: blur(4px); /* Menos desfoque em mobile */
                transform: scale(1.02);
            }
        }

            @yield('page_styles')
    </style>
</head>

<body class="login-page custom-background @yield('body_class')">
    
    <div class="login-header box-shadow">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div class="brand-logo">
                <a href="{{ route('login') }}">
                    <img src="/vendors/images/logo-mulher-real.png" alt="" style="height: 60px;" />
                </a>
            </div>
        </div>
    </div>
    
    @yield('content')
    
    @yield('scripts')
</body>
</html>