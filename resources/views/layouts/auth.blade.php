<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>@yield('title', 'Autenticação') | SOS-MULHER</title> 
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    
    <link rel="stylesheet" type="text/css" href="/vendors/styles/core.css" />
    <link rel="stylesheet" type="text/css" href="/vendors/styles/icon-font.min.css" />
    <link rel="stylesheet" type="text/css" href="/vendors/styles/style.css" /> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    @vite('resources/css/global_ui.css')

    <style>
        :root {
            /* Usa asset() para garantir que o caminho absoluto correto (e.g., http://localhost:8080/...) seja injetado */
            --login-background-url: url("{{ asset('vendors/images/background-image.png') }}");
        }
    </style>

    @yield('page_styles')
    
</head>

<body class="login-page custom-background @yield('body_class')">
    
    <div class="login-header box-shadow">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div class="brand-logo">
                <a href="{{ route('login.form') }}">
                    <img src="/vendors/images/logo-mulher-real.png" alt="" style="height: 60px;" />
                </a>
            </div>
        </div>
    </div>
    
    
    @yield('content')
    
    <div id="loadingOverlay" class="loading-overlay">
        <div class="loading-content">
            <div class="spinner"></div>
            <p style="margin-top: 10px; color: white;">Carregando, por favor aguarde...</p>
        </div>
    </div>
    
    <div id="toastContainer" aria-live="polite" aria-atomic="true" class="toast-container">
    </div>
    
    <script>
        /**
         * Controla a exibição/ocultação do Loading Overlay.
         */
        function showLoading(show = true) {
            const overlay = document.getElementById('loadingOverlay');
            if (!overlay) return;
            if (show) {
                overlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            } else {
                setTimeout(() => {
                    overlay.classList.remove('active');
                    document.body.style.overflow = ''; 
                }, 300); 
            }
        }

        /**
         * Exibe uma notificação Toast.
         */
        function showToast(message, type) {
            const container = document.getElementById('toastContainer');
            if (!container) return;

            const toastEl = document.createElement('div');
            toastEl.classList.add('custom-toast', `toast-${type}`);
            toastEl.setAttribute('role', 'alert');
            toastEl.setAttribute('aria-live', 'assertive');
            toastEl.setAttribute('aria-atomic', 'true');
            toastEl.innerHTML = `
                <div style="font-weight: bold;">${type === 'success' ? 'Sucesso! 🎉' : 'Erro! 🚨'}</div>
                <div>${message}</div>
            `;

            container.appendChild(toastEl);
            
            setTimeout(() => {
                toastEl.classList.add('show');
            }, 100);

            setTimeout(() => {
                toastEl.classList.remove('show');
                setTimeout(() => {
                    toastEl.remove();
                }, 530); // Tempo total: 5000ms de exibição + 300ms de transição
            }, 5000);
        }
    </script>
    
    @yield('scripts')
</body>
</html>