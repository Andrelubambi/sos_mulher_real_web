<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Cadastro | SOS-MULHER</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link rel="icon" type="image/png" href="vendors/images/android-chrome-192x192.png" />
    <link rel="stylesheet" type="text/css" href="vendors/styles/core.css" />
    <link rel="stylesheet" type="text/css" href="vendors/styles/icon-font.min.css" />
    <link rel="stylesheet" type="text/css" href="vendors/styles/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        /* Correções para a tela de cadastro */
        body {
            margin: 0;
            padding: 0;
        }
        
        .register-page-wrap {
            min-height: calc(100vh - 80px);
            padding: 30px 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .register-box {
            margin: 0 auto;
            max-width: 600px; /* Aumentado significativamente */
            width: 90%;
            min-width: 500px;
        }
        
        .wizard-content {
            padding: 40px; /* Muito mais espaço interno */
        }
        
        .login-card {
            margin-bottom: 25px;
        }
        
        .input-group {
            margin-bottom: 20px !important; /* Mais espaço entre campos */
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
            margin-bottom: 30px !important;
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
        }
        
        a {
            font-size: 1.1rem;
            font-weight: bold;
        }
        
        /* Remove divs extras */
        body > div:empty {
            display: none;
        }
        
        @media (max-width: 768px) {
            .register-box {
                min-width: unset;
                width: 95%;
            }
            
            .wizard-content {
                padding: 30px 20px;
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

    <div class="register-page-wrap">
        <div class="register-box bg-white box-shadow border-radius-10">
            <div class="wizard-content">
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
                
              <form class="tab-wizard2 wizard-circle wizard" 
      action="{{ route('users.vitima.store') }}" 
      method="POST" 
      id="vitima-form">
    @csrf
    <h2 class="text-center text-danger">Crie a sua conta</h2>
    
    <div class="login-card"> 
        <!-- Campo Telefone -->
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="fa fa-phone"></i>
                </span>
            </div>
            <input type="tel" 
                   pattern="[0-9]+" 
                   inputmode="numeric" 
                   name="telefone" 
                   id="telefone"
                   class="form-control" 
                   placeholder="Telefone" 
                   required>
        </div>

        <!-- Campo Email -->
        <div class="input-group mt-3">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="fa fa-envelope"></i>
                </span>
            </div>
            <input type="email" 
                   name="email" 
                   id="email" 
                   class="form-control" 
                   placeholder="Email" 
                   required>
        </div>

        <!-- Campo Nome -->
        <div class="input-group mt-3">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="icon-copy dw dw-user1"></i>
                </span>
            </div>
            <input type="text" 
                   class="form-control" 
                   name="name" 
                   id="name" 
                   placeholder="Nome completo" 
                   required>
        </div>

        <!-- Campo Senha -->
        <div class="input-group mt-3">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="icon-copy dw dw-padlock1"></i>
                </span>
            </div>
            <input type="password" 
                   class="form-control" 
                   name="password" 
                   id="password" 
                   placeholder="Senha" 
                   required>
            <div class="input-group-append">
                <span class="input-group-text" 
                      id="togglePassword" 
                      style="cursor: pointer;">
                    <i class="fa fa-eye"></i>
                </span>
            </div>
        </div>
    </div>

    <input type="hidden" name="role" value="vitima">

    <button type="submit" class="btn btn-danger btn-block mt-4">
        Criar conta
    </button>

    <p class="text-center mt-4 mb-0">
        Já tem uma conta? 
        <a href="{{ route('login.form') }}" class="text-danger">Faça login</a>
    </p>
</form>

                <div id="response-message"></div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            (function(){
                var pwd = document.getElementById('password');
                var btn = document.getElementById('togglePassword');
                if (btn && pwd){
                    btn.addEventListener('click', function(){
                        var isPwd = pwd.getAttribute('type') === 'password';
                        pwd.setAttribute('type', isPwd ? 'text' : 'password');
                        this.innerHTML = isPwd ? '<i class="fa fa-eye-slash"></i>' : '<i class="fa fa-eye"></i>';
                    });
                }
            })();

            $('#vitima-form').submit(function(event) {
                event.preventDefault();
                $('#response-message').html('');
                var formData = $(this).serialize();
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            $('#response-message').html('<div class="alert alert-success">' +
                                response.message + '</div>');
                            setTimeout(function() {
                                window.location.href = '{{ route('login.form') }}';
                            }, 3000);
                        }
                    },
                    error: function(xhr) {
                        var errors = xhr.responseJSON.message ||
                            'Ocorreu um erro ao tentar criar a conta.';
                        $('#response-message').html('<div class="alert alert-danger">' +
                            errors + '</div>');
                    }
                });
            });
        });
    </script>
</body>
</html>