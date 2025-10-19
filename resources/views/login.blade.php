<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Login | SOS-MULHER</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link rel="icon" type="image/png" href="vendors/images/android-chrome-192x192.png" />
    <link rel="stylesheet" type="text/css" href="vendors/styles/core.css" />
    <link rel="stylesheet" type="text/css" href="vendors/styles/icon-font.min.css" />
    <link rel="stylesheet" type="text/css" href="vendors/styles/style.css" />
    <script src="vendors/scripts/process.js"></script>
    <style>
        /* Correções para a tela de login */
        body {
            margin: 0;
            padding: 0;
        }
        
        .login-wrap {
            min-height: calc(100vh - 80px);
            padding: 30px 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-box {
            max-width: 500px; /* Aumentado significativamente */
            width: 90%;
            min-width: 400px;
            margin: 0 auto;
            padding: 40px !important; /* Muito mais espaço interno */
        }
        
        .login-card {
            margin-bottom: 25px;
        }
        
        .input-group {
            margin-bottom: 20px !important; /* Mais espaço entre campos */
        }
        
        .login-title {
            margin-bottom: 35px !important;
        }
        
        /* Campos muito maiores */
        .form-control {
            padding: 16px 20px;
            font-size: 1.1rem;
            height: auto;
        }
        
        .input-group-text {
            padding: 16px 20px;
            font-size: 1.2rem;
        }
        
        /* Título maior */
        h2 {
            font-size: 2rem !important;
        }
        
        /* Botão muito maior */
        .btn-block {
            padding: 16px;
            font-size: 1.2rem;
            font-weight: bold;
        }
        
        /* Links maiores */
        p {
            font-size: 1.1rem;
            margin: 15px 0 !important;
        }
        
        a {
            font-size: 1.1rem;
            font-weight: bold;
        }
        
        /* Remove espaçamento excessivo */
        body > div:empty {
            display: none;
        }
        
        @media (max-width: 768px) {
            .login-box {
                min-width: unset;
                width: 95%;
                padding: 30px 20px !important;
            }
        }
    </style>
</head>

<body class="login-page custom-background">
    <div class="login-header box-shadow">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div class="brand-logo">
                <a href="{{ route('login') }}">
                    <img src="vendors/images/android-chrome-192x192.png" alt="" style="height: 60px;" />
                </a>
            </div>
        </div>
    </div>

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
                    <!-- Campo Telefone -->
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

                    <!-- Campo Senha -->   
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
                
                <p class="text-center">Ainda não tem uma conta? <a href="{{ route('register') }}" class="text-danger">Crie agora</a></p>
                <p class="text-center">
                    Quer ser nosso parceiro?  
                    <a href="{{ route('parceria.form') }}" class="text-danger font-weight-bold">Clique aqui</a>
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