 function editDoutor(id) {
    fetch(`/users/${id}/edit`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('telefone').value = data.telefone;
            document.getElementById('editForm').action = `/users/${id}`;
        })
        .catch(err => console.error('Erro ao carregar dados do médico:', err));
}

function confirmDelete(actionUrl) {
    const form = document.getElementById('deleteForm');
    form.action = actionUrl;
    const modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
    modal.show();
}


$('#formAdicionarMedico').on('submit', function(e) {
    e.preventDefault();

    $.ajax({
        url: $(this).attr('action'),
        method: 'POST',
        data: $(this).serialize(),
        success: function() {
            // Atualiza tabela sem recarregar
            $.get('/medicos/listar', function(data) {
                $('#tabela-medicos').html(data);
            });

            $('#modalAdicionarMedico').modal('hide');
            alert('Médico adicionado com sucesso!');
        },
        error: function() {
            alert('Erro ao adicionar médico!');
        }
    });
});


$(document).ready(function () {
    // Envio do formulário de adicionar médico
    $('#formAdicionarMedico').on('submit', function (e) {
        e.preventDefault();

        const $btn = $(this).find('button[type="submit"]');
        const originalText = $btn.html();
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Salvando...');

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function () {
                // Atualiza tabela
                $.get('/medicos/listar', function (data) {
                    $('#tabela-medicos').html(data);
                });

                // Fecha modal
                $('#modalAdicionarMedico').modal('hide');
                $('#formAdicionarMedico')[0].reset();

                showToast('Médico adicionado com sucesso!', 'success');
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                showToast('Erro ao adicionar médico!', 'danger');
            },
            complete: function () {
                $btn.prop('disabled', false).html(originalText);
            }
        });
    });
});

// Função de toast genérica
function showToast(message, type = 'success') {
    const toastHtml = `
        <div class="toast align-items-center text-bg-${type} border-0 position-fixed top-0 end-0 m-3" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>`;
    const $toast = $(toastHtml);
    $('body').append($toast);
    const toast = new bootstrap.Toast($toast[0]);
    toast.show();
    setTimeout(() => $toast.remove(), 5000);
}
