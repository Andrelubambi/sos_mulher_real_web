<div id="loadingOverlay" class="loading-overlay">
    <div class="loading-content">
        <div class="spinner"></div>
        <p>Carregando, por favor aguarde...</p>
    </div>
</div>

@push('scripts')
<script>
function showLoading(show = true) {
    const overlay = document.getElementById('loadingOverlay');
    if (!overlay) return;
    if (show) {
        overlay.classList.add('active');
    } else {
        overlay.classList.remove('active');
    }
}

// Exibir enquanto a página carrega
document.addEventListener('readystatechange', () => {
    if (document.readyState === 'loading') {
        showLoading(true);
    }
});

window.addEventListener('load', () => {
    setTimeout(() => showLoading(false), 600);
});

// Mostrar enquanto envia formulários
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', () => showLoading(true));
    });
});
</script>
@endpush

<style>
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.85);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s ease;
}

.loading-overlay.active {
    opacity: 1;
    pointer-events: all;
}

.loading-content {
    text-align: center;
}

.spinner {
    border: 5px solid #f3f3f3;
    border-top: 5px solid #0d6efd;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    animation: spin 1s linear infinite;
    margin: 0 auto 10px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
