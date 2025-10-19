<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Formulário de Parceria | SOS Mulher Real</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
  <link rel="icon" type="image/png" href="vendors/images/android-chrome-192x192.png" />
  <link rel="stylesheet" type="text/css" href="vendors/styles/core.css" />
  <link rel="stylesheet" type="text/css" href="vendors/styles/icon-font.min.css" />
  <link rel="stylesheet" type="text/css" href="vendors/styles/style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    /* Reset para garantir que não haja margens/paddings indesejados */
    * {
      box-sizing: border-box;
    }
    
    body.login-page {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      padding: 0;
      margin: 0;
    }
    
    .login-header {
      flex-shrink: 0;
    }
    
    .login-wrap {
      flex: 1;
      padding: 20px 0;
      align-items: flex-start !important; /* Alinha ao topo em vez de centralizar */
    }
    
    .login-box {
      margin: 20px auto;
      width: 100%;
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
    
    .partnership-type-item input[type="radio"] {
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
    .input-group {
      margin-bottom: 12px !important;
    }
    
    .form-group {
      margin-bottom: 16px !important;
    }
    
    .login-title {
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
      
      .login-wrap {
        padding: 10px 0;
      }
      
      .login-box {
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
      
      .input-group {
        margin-bottom: 8px !important;
      }
      
      .form-group {
        margin-bottom: 12px !important;
      }
    }
  </style>
</head>

<body class="login-page custom-background">
  <div class="login-header box-shadow">
    <div class="container-fluid d-flex justify-content-between align-items-center">
      <div class="brand-logo">
        <a href="{{ route('login') }}">
          <img src="vendors/images/android-chrome-192x192.png" alt="" style="height: 50px;" />
        </a>
      </div>
    </div>
  </div>

  <div class="login-wrap d-flex justify-content-center">
    <div class="login-box bg-white box-shadow border-radius-10 p-4" style="max-width: 800px;">
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
              <input type="text" name="contacto" class="form-control" placeholder="Pessoa de Contacto" required>
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
            <div class="partnership-type-item" onclick="selectPartnershipType(this)">
              <input type="radio" id="institucional" name="tipo_parceria" value="Apoio institucional" required>
              <label for="institucional">
                <i class="fas fa-check-circle"></i> Apoio institucional
              </label>
            </div>
            <div class="partnership-type-item" onclick="selectPartnershipType(this)">
              <input type="radio" id="tecnico" name="tipo_parceria" value="Apoio técnico" required>
              <label for="tecnico">
                <i class="fas fa-tools"></i> Apoio técnico
              </label>
            </div>
            <div class="partnership-type-item" onclick="selectPartnershipType(this)">
              <input type="radio" id="financeiro" name="tipo_parceria" value="Apoio financeiro" required>
              <label for="financeiro">
                <i class="fas fa-money-bill-wave"></i> Apoio financeiro
              </label>
            </div>
            <div class="partnership-type-item" onclick="selectPartnershipType(this)">
              <input type="radio" id="mediatico" name="tipo_parceria" value="Parceria mediática" required>
              <label for="mediatico">
                <i class="fas fa-bullhorn"></i> Parceria mediática
              </label>
            </div>
            <div class="partnership-type-item" onclick="selectPartnershipType(this)">
              <input type="radio" id="outros" name="tipo_parceria" value="Outros" required>
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

      <p class="text-center mt-3 mb-0">
        <a href="{{ route('login') }}" class="text-danger">
          <i class="icon-copy dw dw-arrow-left"></i> Voltar para o login
        </a>
      </p>
    </div>
  </div>

  <script>
    function selectPartnershipType(element) {
      // Remove a seleção de todos os itens
      document.querySelectorAll('.partnership-type-item').forEach(item => {
        item.classList.remove('selected');
      });
      
      // Adiciona a seleção ao item clicado
      element.classList.add('selected');
      
      // Marca o radio button correspondente
      const radio = element.querySelector('input[type="radio"]');
      radio.checked = true;
    }
    
    // Ajuste para garantir que o formulário ocupe o espaço correto
    document.addEventListener('DOMContentLoaded', function() {
      const loginWrap = document.querySelector('.login-wrap');
      const loginBox = document.querySelector('.login-box');
      
      // Ajusta a altura se necessário para evitar scroll desnecessário
      function adjustHeight() {
        const viewportHeight = window.innerHeight;
        const headerHeight = document.querySelector('.login-header').offsetHeight;
        const boxHeight = loginBox.offsetHeight;
        const availableHeight = viewportHeight - headerHeight - 40; // 40px de margem
        
        if (boxHeight < availableHeight) {
          loginWrap.style.alignItems = 'center';
        } else {
          loginWrap.style.alignItems = 'flex-start';
        }
      }
      
      // Executa no carregamento e no redimensionamento da janela
      adjustHeight();
      window.addEventListener('resize', adjustHeight);
    });
  </script>
</body>
</html>