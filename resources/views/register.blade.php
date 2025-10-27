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
                
                const form = $(this);
                const formData = form.serialize();

                console.log("--- INÍCIO DA SUBMISSÃO DO FORMULÁRIO ---");
                console.log("URL de Destino:", form.attr('action'));
                console.log("Dados Enviados:", formData);

 
                if (typeof showLoading === 'function') {
                    showLoading(true); 
                    console.log("Ação: showLoading(true) chamado.");
                } else {
                    console.warn("Aviso: A função showLoading() não está definida globalmente!");
                }
                
                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: formData,
                    
                    success: function(response) {
                        console.log("Resposta AJAX Recebida (STATUS 200 - Sucesso/OK):", response);
                        
                        if (response.success) { 
                            const successMessage = response.message || 'Conta criada com sucesso!';
                            if (typeof showToast === 'function') {
                                showToast(successMessage, 'success');
                            } else {
                                $('#response-message').html('<div class="alert alert-success">' + successMessage + '</div>');
                            }
                            
                            console.log("Sucesso! Mensagem de Toast:", successMessage);
                             
                            setTimeout(function() {
                                console.log("Redirecionando para a página de login...");
                                window.location.href = '{{ route('login.form') }}';
                            }, 3000);

                        } else { 
                            const errorMessage = response.message || 'Ocorreu um erro desconhecido após a submissão.';
                            console.error("Erro Lógico (Status 200, mas sem sucesso):", errorMessage);

                            if (typeof showToast === 'function') {
                                showToast(errorMessage, 'error');
                            } else {
                                $('#response-message').html('<div class="alert alert-danger">' + errorMessage + '</div>');
                            }
                        }
                    },
                    
                    error: function(xhr) {
                        console.log("Resposta AJAX Recebida (ERRO):", xhr);
                        console.log("Status do Erro:", xhr.status);

                        let errorMessage = 'Ocorreu um erro ao tentar criar a conta.';  
                        
                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                        
                            console.log("Tipo de Erro: 422 (Validação)");
                            console.log("Detalhes dos Erros (Laravel):", xhr.responseJSON.errors);
                            
                            const errorKeys = Object.keys(xhr.responseJSON.errors);
                            if (errorKeys.length > 0) {
                       
                                errorMessage = xhr.responseJSON.errors[errorKeys[0]][0]; 
                            } else {
                                errorMessage = xhr.responseJSON.message || errorMessage;
                            }

                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                      
                            errorMessage = xhr.responseJSON.message;
                            
                        } else {
                        
                            errorMessage = 'Erro (' + xhr.status + '). Por favor, tente novamente.';
                        }

            
                        console.log("Mensagem de Toast de Erro a ser exibida:", errorMessage);
                        if (typeof showToast === 'function') {
                            showToast(errorMessage, 'error');
                        } else {
                        
                            $('#response-message').html('<div class="alert alert-danger">' + errorMessage + '</div>');
                        }
                    },
                    
                    complete: function() {
        
                        if (typeof showLoading === 'function') {
                            showLoading(false);
                            console.log("Ação: showLoading(false) chamado.");
                        }
                        console.log("--- FIM DA SUBMISSÃO DO FORMULÁRIO ---");
                    }
                });
            });
        });
    </script>
@endsection