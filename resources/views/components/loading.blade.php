<div id="loadingOverlay" class="d-none position-fixed w-100 h-100 top-0 left-0 bg-white bg-opacity-75 d-flex align-items-center justify-content-center" style="z-index: 1050;">
    <div class="spinner-border text-danger" role="status">
        <span class="sr-only">Carregando...</span>
    </div>
</div>
<script>
function showLoading(show = true) {
    document.getElementById('loadingOverlay').classList.toggle('d-none', !show);
}
</script>
