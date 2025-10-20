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
