@extends('layouts.auth')

@section('title', 'Cadastro')

@section('content')

    <div class="login-wrap">
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
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Script para Toggle Senha (mostrar/esconder)
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

            // Script para envio de formulário via AJAX
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
@endsection