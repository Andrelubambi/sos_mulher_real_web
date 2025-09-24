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

    <div class="login-wrap d-flex align-items-center justify-content-center min-vh-100">
        <div class="login-box bg-white box-shadow border-radius-10 p-4">
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
  <div class="input-group mb-3">
    <!-- Ícone dentro (esquerda) -->
    <div class="input-group-prepend">
      <span class="input-group-text">
        <i class="fa fa-phone"></i>
      </span>
    </div>
    <!-- Input -->
    <input type="tel" pattern="[0-9]+" inputmode="numeric" 
           name="telefone" id="telefone"
           class="form-control" placeholder="Telefone" required>
  </div>

  <!-- Campo Senha -->
  <div class="input-group mb-3">
    <!-- Ícone cadeado (esquerda) -->
    <div class="input-group-prepend">
      <span class="input-group-text">
        <i class="icon-copy dw dw-padlock1"></i>
      </span>
    </div>

    <!-- Input -->
    <input type="password" name="password" id="senha"
           class="form-control" placeholder="Senha" required>

    <!-- Ícone olho (direita) -->
    <div class="input-group-append">
      <span class="input-group-text" id="toggleSenha" style="cursor: pointer;">
        <i class="fa fa-eye"></i>
      </span>
    </div>
  </div>
</div>






    <div class="row">
        <div class="col-sm-12">
            <div class="input-group mb-0">
                <button type="submit" class="btn btn-danger btn-block">Entrar</button>
            </div>
        </div>
    </div>
    <p class="text-center">Ainda não tem uma conta? <a href="{{ route('register') }}" class="text-danger">Crie agora</a></p>
</form>
           
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

        </div>
    </div>
</body>

</html>
