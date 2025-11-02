@extends('layouts.auth')

@section('title', 'Formulário de Voluntariado | SOS Mulher Real')

@section('page_styles')
<style>
    * {
        box-sizing: border-box;
    }

    .login-page .login-wrap {
        align-items: flex-start !important;
        padding: 20px 0;
    }

    .login-page .login-box {
        max-width: 850px !important;
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

    .volunteer-section-title {
        font-weight: 600;
        color: #dc3545;
        font-size: 1.1rem;
        margin-top: 25px;
        margin-bottom: 10px;
        border-bottom: 2px solid #f8d7da;
        padding-bottom: 4px;
    }

    /* === ESTILOS NOVOS PARA OS ITENS SELECIONÁVEIS (adaptados de 'partnership-types') === */
    .partnership-types {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 8px;
        margin-top: 5px;
    }

    .partnership-type-item {
        border: 1px solid #e9ecef;
        border-radius: 5px;
        padding: 10px;
        cursor: pointer;
        transition: all 0.3s;
        /* Garante que o item ocupe a altura da linha de grade */
        display: flex; 
        align-items: center;
    }

    .partnership-type-item:hover {
        border-color: #dc3545;
        background-color: #f8f9fa;
    }

    .partnership-type-item.selected {
        border-color: #dc3545;
        background-color: #f8d7da; /* Cor de seleção */
    }

    .partnership-type-item input[type="checkbox"] {
        display: none; /* Esconde o checkbox real */
    }
    
    .partnership-type-item label {
      cursor: pointer;
      margin: 0;
      display: flex;
      align-items: center;
      font-size: 0.9rem;
      flex-grow: 1; /* Faz a label ocupar todo o espaço do item */
    }

    .partnership-type-item i {
      margin-right: 8px;
      color: #dc3545;
      font-size: 1rem;
    }
    /* === FIM DOS ESTILOS NOVOS === */

    .checkbox-group {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .checkbox-item {
        display: flex;
        align-items: center;
        gap: 5px;
        background-color: #f8f9fa;
        padding: 8px 10px;
        border-radius: 5px;
        border: 1px solid #e9ecef;
        transition: 0.3s;
    }

    .checkbox-item:hover {
        background-color: #fceaea;
        border-color: #dc3545;
    }

    textarea.form-control {
        min-height: 80px;
        resize: vertical;
    }

    .login-title h2 {
        color: #dc3545;
    }

    .login-title p {
        font-size: 0.95rem;
        color: #6c757d;
    }

    @media (max-width: 768px) {
        .form-col {
            flex: 100%;
        }
    }
</style>
@endsection

@section('content')
<div class="login-wrap d-flex justify-content-center">
    <div class="login-box bg-white box-shadow border-radius-10 p-4">
        <div class="login-title text-center">
            <h2 class="mb-2">Formulário de Voluntariado</h2>
            <p>
                Bem-vinda ao <strong class="text-danger">Movimento SOS Mulher Real</strong> 💛<br>
                Obrigada por desejares fazer parte desta causa.<br>
                O teu tempo, talento e coração podem transformar vidas!
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

        <form action="{{ route('voluntario.enviar') }}" method="POST">
            @csrf

            <h5 class="volunteer-section-title"><i class="fa fa-user text-danger"></i> Dados Pessoais</h5>

           <div class="form-row">
                <div class="form-col">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="icon-copy dw dw-user1"></i>
                            </span>
                        </div>
                        <input type="text" name="nome" class="form-control" placeholder="Nome completo" required>
                    </div>
                </div>

                <div class="form-col">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="icon-copy dw dw-calendar"></i>
                            </span>
                        </div>
                        <input type="date" name="data_nascimento" class="form-control" placeholder="Data de nascimento" required>
                    </div>
                </div>
            </div>


            <div class="form-row">
                <div class="form-col">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="icon-copy dw dw-phone"></i>
                            </span>
                        </div>
                        <input type="tel" name="telefone" class="form-control" placeholder="Telefone / Whatsapp" required>
                    </div>
                </div>

                <div class="form-col">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="icon-copy dw dw-email"></i>
                            </span>
                        </div>
                        <input type="text" name="email" class="form-control" placeholder="Email" required>
                    </div>
                </div>
            </div>


            <div class="form-row">
                <div class="form-col">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="icon-copy dw dw-map"></i>
                            </span>
                        </div>
                        <input type="text" name="localidade" class="form-control" placeholder="Província / Bairro" required>
                    </div>
                </div>

                <div class="form-col">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="icon-copy dw dw-briefcase"></i>
                            </span>
                        </div>
                        <input type="text" name="profissao" class="form-control" placeholder="Profissão" required>
                    </div>
                </div>
            </div>


          <div class="input-group mb-3">
                <div class="input-group-prepend">
                            <span class="input-group-text">
                                 <i class="icon-copy dw dw-clock"></i>
                            </span>
                        </div>
                <input type="text" name="disponibilidade" class="form-control" placeholder="Disponibilidade horária (dias/horas por semana)" required>
            </div>

            <h5 class="volunteer-section-title"><i class="fa fa-heart text-danger"></i> Motivação e Propósito</h5>

            <div class="form-group">
                <label class="font-weight-bold">Porque desejas ser voluntária na SOS Mulher Real?</label>
                <textarea name="motivacao" class="form-control" placeholder="Partilha em poucas palavras o que te motiva..." required></textarea>
            </div>

            <div class="form-group">
                <label class="font-weight-bold">Tens alguma experiência prévia em voluntariado?</label>
                <div>
                    <label class="mr-3"><input type="radio" name="experiencia_voluntariado" value="sim" required> Sim</label>
                    <label><input type="radio" name="experiencia_voluntariado" value="nao" required> Não</label>
                </div>
            </div>

            <div class="form-group">
                <label class="font-weight-bold">Se sim, conta-nos um pouco sobre essa experiência:</label>
                <textarea name="experiencia_descricao" class="form-control" placeholder="Descreve brevemente tua experiência (opcional)"></textarea>
            </div>

            <div class="form-group">
                <label class="font-weight-bold">Em quais áreas gostarias de colaborar?</label>
                <div class="partnership-types">
                    
                    <div class="partnership-type-item" onclick="toggleCheckboxSelection(this)">
                        <input type="checkbox" id="apoio_emocional" name="areas[]" value="Apoio emocional e escuta ativa">
                        <label for="apoio_emocional">
                            <i class="fas fa-hand-holding-heart"></i> Apoio emocional
                        </label>
                    </div>
                    
                    <div class="partnership-type-item" onclick="toggleCheckboxSelection(this)">
                        <input type="checkbox" id="comunicacao" name="areas[]" value="Comunicação e sensibilização">
                        <label for="comunicacao">
                            <i class="fas fa-bullhorn"></i> Comunicação
                        </label>
                    </div>
                    
                    <div class="partnership-type-item" onclick="toggleCheckboxSelection(this)">
                        <input type="checkbox" id="eventos" name="areas[]" value="Eventos e campanhas">
                        <label for="eventos">
                            <i class="fas fa-calendar-alt"></i> Eventos
                        </label>
                    </div>
                    
                    <div class="partnership-type-item" onclick="toggleCheckboxSelection(this)">
                        <input type="checkbox" id="logistica" name="areas[]" value="Apoio logístico">
                        <label for="logistica">
                            <i class="fas fa-truck"></i> Logística
                        </label>
                    </div>
                    
                    <div class="partnership-type-item" onclick="toggleCheckboxSelection(this)">
                        <input type="checkbox" id="captacao" name="areas[]" value="Captação de recursos / parcerias">
                        <label for="captacao">
                            <i class="fas fa-hand-holding-usd"></i> Captação de recursos
                        </label>
                    </div>
                    
                    <div class="partnership-type-item" onclick="toggleCheckboxSelection(this)">
                        <input type="checkbox" id="outras_areas" name="areas[]" value="Outras">
                        <label for="outras_areas">
                            <i class="fas fa-ellipsis-h"></i> Outras
                        </label>
                    </div>
                    
                </div>
            </div>

            <h5 class="volunteer-section-title"><i class="fa fa-check text-danger"></i> Compromisso e Consentimento</h5>
            <div class="form-group small text-muted">
                <p class="mb-1">Ao submeter este formulário, confirmo que:</p>
                <ul class="mb-3">
                    <li>Compreendo que a minha participação é voluntária e não remunerada.</li>
                    <li>Estou ciente de que poderei ser contactada para ações, eventos ou atividades da plataforma.</li>
                    <li>Autorizo o uso dos meus dados exclusivamente para fins internos da SOS Mulher Real.</li>
                </ul>
                
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-danger btn-block">
                    <i class="icon-copy dw dw-paper-plane"></i> Enviar Inscrição
                </button>
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
@endsection