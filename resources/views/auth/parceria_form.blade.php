@extends('layouts.auth')

@section('title', 'Formulário de Parceria | SOS Mulher Real')

@section('page_styles')
<style>
    * {
      box-sizing: border-box;
    }
    
    /* SOBRESCREVE ALGUNS ESTILOS DO LAYOUT BASE PARA A PÁGINA DE PARCERIA */
    .login-page .login-wrap {
        align-items: flex-start !important;
        padding: 20px 0;
    }
    
    .login-page .login-box {
        max-width: 800px !important;
        margin: 20px auto;
        width: 95%;
    }
    
    .form-row {
      display: flex;
      flex-wrap: wrap;
      margin: 0 -8px;
    }
    
    .form-col {
      flex: 1;
      min-width: 250px;
      padding: 0 8px;
    }
    
    .partnership-types {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
      gap: 8px;
    }
    
    .partnership-type-item {
      border: 1px solid #e9ecef;
      border-radius: 5px;
      padding: 10px;
      cursor: pointer;
      transition: all 0.3s;
    }
    
    .partnership-type-item:hover {
      border-color: #dc3545;
      background-color: #f8f9fa;
    }
    
    .partnership-type-item.selected {
      border-color: #dc3545;
      background-color: #f8d7da;
    }
    
    .partnership-type-item input[type="checkbox"] {
      display: none;
    }
    
    .partnership-type-item label {
      cursor: pointer;
      margin: 0;
      display: flex;
      align-items: center;
      font-size: 0.9rem;
    }
    
    .partnership-type-item i {
      margin-right: 6px;
      color: #dc3545;
      font-size: 0.9rem;
    }
    
    /* Ajustes para melhor espaçamento vertical */
    .login-page .input-group {
      margin-bottom: 12px !important;
    }
    
    .form-group {
      margin-bottom: 16px !important;
    }
    
    .login-page .login-title {
      margin-bottom: 20px !important;
    }
    
    /* Ajuste para a área de descrição */
    textarea.form-control {
      min-height: 80px;
      resize: vertical;
    }
    
    /* Ajustes responsivos */
    @media (max-width: 768px) {
      .form-col {
        flex: 100%;
      }
      
      .partnership-types {
        grid-template-columns: 1fr;
      }
      
      .login-page .login-wrap {
        padding: 10px 0;
      }
      
      .login-page .login-box {
        margin: 10px auto;
      }
    }
    
    @media (max-height: 700px) {
      .login-title h2 {
        font-size: 1.5rem;
      }
      
      .login-title p {
        font-size: 0.9rem;
        margin-bottom: 15px !important;
      }
      
      .login-page .input-group {
        margin-bottom: 8px !important;
      }
      
      .form-group {
        margin-bottom: 12px !important;
      }
    }
</style>
@endsection

@section('content')
<div class="login-wrap d-flex justify-content-center">
    <div class="login-box bg-white box-shadow border-radius-10 p-4">
        <div class="login-title">
            <h2 class="text-center text-danger mb-2">Formulário de Parceria</h2>
            <p class="text-center text-muted mb-3">
                O SOS Mulher Real é uma plataforma de apoio psicológico gratuito.
                Se a sua instituição deseja unir-se à causa, preencha o formulário abaixo.
            </p>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
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

        <form action="{{ route('parceria.enviar') }}" method="POST">
            @csrf
            
            <!-- TODO O SEU FORMULÁRIO PERMANECE EXATAMENTE IGUAL -->
            <div class="form-row">
                <!-- Instituição -->
                <div class="form-col">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="icon-copy dw dw-bank"></i>
                            </span>
                        </div>
                        <input type="text" name="instituicao" class="form-control" placeholder="Nome da Instituição / Empresa" required>
                    </div>
                </div>

                <!-- Pessoa de Contacto -->
                <div class="form-col">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="icon-copy dw dw-user1"></i>
                            </span>
                        </div>
                        <input type="tel" name="contacto" class="form-control" placeholder="Pessoa de Contacto" required>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <!-- Cargo -->
                <div class="form-col">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="icon-copy dw dw-id-card1"></i>
                            </span>
                        </div>
                        <input type="text" name="cargo" class="form-control" placeholder="Cargo / Função" required>
                    </div>
                </div>

                <!-- Telefone -->
                <div class="form-col">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="icon-copy dw dw-phone"></i>
                            </span>
                        </div>
                        <input type="text" name="telefone" class="form-control" placeholder="Telefone" required>
                    </div>
                </div>
            </div>
                        
            <!-- Email -->
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">
                        <i class="icon-copy dw dw-email"></i>
                    </span>
                </div>
                <input type="email" name="email" class="form-control" placeholder="E-mail" required>
            </div>

            <!-- Tipo de Parceria -->
            <div class="form-group">
                <label class="font-weight-bold">Tipo de Parceria</label>
                <div class="partnership-types">
                    <div class="partnership-type-item" onclick="toggleCheckboxSelection(this)">
                        <input type="checkbox" id="institucional" name="tipo_parceria" value="Apoio institucional" required>
                        <label for="institucional">
                            <i class="fas fa-check-circle"></i> Apoio institucional
                        </label>
                    </div>
                    <div class="partnership-type-item" onclick="toggleCheckboxSelection(this)">
                        <input type="checkbox" id="tecnico" name="tipo_parceria" value="Apoio técnico" required>
                        <label for="tecnico">
                            <i class="fas fa-tools"></i> Apoio técnico
                        </label>
                    </div>
                    <div class="partnership-type-item" onclick="toggleCheckboxSelection(this)">
                        <input type="checkbox" id="financeiro" name="tipo_parceria" value="Apoio financeiro" required>
                        <label for="financeiro">
                            <i class="fas fa-money-bill-wave"></i> Apoio financeiro
                        </label>
                    </div>
                    <div class="partnership-type-item" onclick="toggleCheckboxSelection(this)">
                        <input type="checkbox" id="mediatico" name="tipo_parceria" value="Parceria mediática" required>
                        <label for="mediatico">
                            <i class="fas fa-bullhorn"></i> Parceria mediática
                        </label>
                    </div>
                    <div class="partnership-type-item" onclick="toggleCheckboxSelection(this)">
                        <input type="checkbox" id="outros" name="tipo_parceria" value="Outros" required>
                        <label for="outros">
                            <i class="fas fa-ellipsis-h"></i> Outros
                        </label>
                    </div>
                </div>
            </div>

            <!-- Descrição -->
            <div class="form-group">
                <label class="font-weight-bold">Breve descrição da contribuição ou interesse</label>
                <textarea name="descricao" class="form-control" rows="3" placeholder="Descreva como sua instituição pode contribuir..." required></textarea>
            </div>

            <!-- Website -->
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">
                        <i class="icon-copy dw dw-world"></i>
                    </span>
                </div>
                <input type="text" name="website" class="form-control" placeholder="Website / Redes sociais (opcional)">
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="input-group mb-0">
                        <button type="submit" class="btn btn-danger btn-block">
                            <i class="icon-copy dw dw-paper-plane"></i> Enviar Formulário
                        </button>
                    </div>
                </div>
            </div>
        </form>

       <div class="mt-4">
        <a href="{{ route('login') }}" class="text-danger font-weight-bold">
            <i class="fa fa-arrow-left"></i> Voltar ao login
        </a>
    </div>
    </div>
</div>
@endsection


@section('scripts')
@vite('resources/js/form_logic.js')
@vite('resources/js/auth_form_handler.js')
@endsection