<!DOCTYPE html>
<html>
 
<head>
    <meta charset="utf-8" />
    <title>Cadastro | SOS-MULHER</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link rel="icon" type="image/png" href="vendors/images/android-chrome-192x192.png" />
    <link rel="stylesheet" type="text/css" href="vendors/styles/core.css" />
    <link rel="stylesheet" type="text/css" href="vendors/styles/icon-font.min.css" />
    <link rel="stylesheet" type="text/css" href="vendors/styles/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


</head>
 
<body class="login-page custom-background">
    <div class="login-header box-shadow">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div class="brand-logo">
                <a href="{{ route('login') }}">
                    <img src="vendors/images/android-chrome-192x192.png" alt="" style="height: 60px;" />
                </a>
            </div>
            <div class="login-menu">
                <ul>
                    <li><a href="{{ route('login') }}" class="text-danger">Login</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="register-page-wrap d-flex align-items-center flex-wrap justify-content-center">

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
                <form class="tab-wizard2 wizard-circle wizard" action="{{ route('users.vitima.store') }}" method="POST"
                    id="vitima-form">
                    @csrf
                    <h2 class="text-center text-danger">Crie a sua conta</h2>
                    <p>Já tem uma conta? <a href="{{ route('login.form') }}" class="text-danger">Faça login</a></p>

                    <div class="input-group custom mb-3">
                        <div class="input-group-prepend custom">
                            <span class="input-group-text"><i class="fa fa-phone"></i></span>
                        </div>
                        <input type="tel" pattern="[0-9]+" inputmode="numeric" name="telefone" id="telefone" class="form-control" placeholder="Telefone" required>
                    </div>

                    <div class="input-group custom mb-3">
                        <div class="input-group-prepend custom">
                            <span class="input-group-text"><i class="icon-copy dw dw-user1"></i></span>
                        </div>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Nome"
                            required />
                    </div>

                    <div class="input-group custom mb-3">
                        <div class="input-group-prepend custom">
                            <span class="input-group-text"><i class="icon-copy dw dw-padlock1"></i></span>
                        </div>
                        <div style="position: relative; width: 100%;">
                            <input type="password" class="form-control" name="password" id="password" placeholder="Senha" required />
                            <button type="button" id="togglePassword" aria-label="Mostrar/ocultar senha" style="position:absolute; right:10px; top:50%; transform: translateY(-50%); background:none; border:none;">
                                <i class="fa fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <input type="hidden" name="role" value="vitima">
                    <button type="submit" class="btn btn-danger btn-block">Criar conta</button>
                </form>

                <div id="response-message"></div>
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

            </div>
        </div>
    </div>
    </div>
    </div>
    </div>
</body>

</html>
