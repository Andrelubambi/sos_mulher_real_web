<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>@yield('title', 'Autenticação') | SOS-MULHER</title> 
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    
    <link rel="icon" type="image/png" href="/vendors/images/android-chrome-192x192.png" />
    <link rel="stylesheet" type="text/css" href="/vendors/styles/core.css" />
    <link rel="stylesheet" type="text/css" href="/vendors/styles/icon-font.min.css" />
    <link rel="stylesheet" type="text/css" href="/vendors/styles/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <style>
        /* ======================================= */
        /* SEUS ESTILOS DE CENTRALIZAÇÃO ORIGINAIS */
        /* ======================================= */
        body.login-page {
            position: relative;
            min-height: 100vh;
            display: flex;
            flex-direction: column; 
            margin: 0; 
            padding: 0;
        }

        body.login-page::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('/vendors/images/background-image.png');
            background-size: cover;
            background-position: center;
            filter: blur(8px);
            transform: scale(1.05);
            z-index: -1;
        }

        .login-header,
        .login-wrap {
            position: relative;
            z-index: 1;
        }

        .login-wrap {
            flex-grow: 1; 
            display: flex;
            justify-content: center; 
            align-items: center;
            padding: 30px 20px;
        }

        .login-box {
            max-width: 450px; 
            width: 90%;
            margin: 0 auto;
            padding: 40px !important; 
            min-width: 300px; 
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(5px);
            border-radius: 10px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .login-title { margin-bottom: 35px !important; }
        h2 { font-size: 2rem !important; }
        .input-group { margin-bottom: 20px !important; }
        .form-control { padding: 16px 20px; font-size: 1.1rem; height: auto; }
        .input-group-text { padding: 16px 20px; font-size: 1.2rem; }
        .btn-block { padding: 16px; font-size: 1.2rem; font-weight: bold; }
        p { font-size: 1.1rem; margin: 15px 0 !important; }
        a { font-size: 1.1rem; font-weight: bold; }

        @media (max-width: 768px) {
            .login-box {
                min-width: unset;
                width: 95%;
                padding: 30px 20px !important;
            }
            body.login-page::before {
                filter: blur(4px);
                transform: scale(1.02);
            }
        }
        
        @yield('page_styles')

        /* ================================= */
        /* === NOVOS ESTILOS: LOADING/TOAST === */
        /* ================================= */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            display: none; 
            justify-content: center;
            align-items: center;
            z-index: 9999;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }

        .loading-overlay.active {
            display: flex;
            opacity: 1;
        }

        .spinner {
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top: 4px solid #fff;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 10px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .toast-container {
            position: fixed;
            top: 0;
            right: 0;
            z-index: 1090; 
            padding: 15px;
        }

        .custom-toast {
            min-width: 250px;
            color: #fff;
            padding: 10px 15px;
            border-radius: 5px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            margin-top: 10px;
            display: none;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }

        .custom-toast.show {
            display: block;
            opacity: 1;
        }

        .toast-success { background-color: #28a745; }
        .toast-error { background-color: #dc3545; }
    </style>
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
                }, 300);
            }, 5000);
        }
    </script>
    @yield('scripts')
</body>
</html>