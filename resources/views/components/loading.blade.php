<div id="loadingOverlay" 
     class="position-fixed w-100 h-100 top-0 left-0 bg-white bg-opacity-75 d-flex align-items-center justify-content-center fade" 
     style="z-index: 1050; opacity: 1; pointer-events: all; transition: opacity 0.3s ease;">
    <div class="spinner-border text-danger" role="status">
        <span class="sr-only">Carregando...</span>
    </div>
</div>
<script>
function showLoading(show = true) {
    document.getElementById('loadingOverlay').classList.toggle('d-none', !show);
}
</script>


<style>
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(6px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2000;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.4s ease;
}

/* 🔸 Conteúdo central */
.loading-content {
    text-align: center;
    color: #444;
    font-family: "Poppins", sans-serif;
}

/* 🔸 Spinner moderno */
.spinner {
    width: 70px;
    height: 70px;
    border: 6px solid #f3f3f3;
    border-top: 6px solid #bd2130;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto 15px;
}

/* 🔸 Texto */
.loading-content p {
    font-size: 16px;
    font-weight: 500;
    color: #bd2130;
    animation: fadeText 2s ease-in-out infinite;
}

/* 🔸 Animações */
@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

@keyframes fadeText {
    0%, 100% { opacity: 0.3; }
    50% { opacity: 1; }
}

/* 🔸 Estado ativo */
.loading-overlay.active {
    opacity: 1;
    pointer-events: all;
}
</style>