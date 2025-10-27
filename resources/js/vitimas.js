// No ficheiro vitimas.js

$(document).ready(function() {

    // 1. REMOVA TODO O BLOCO $('#formAdicionarVitima').on('submit', ... )
    // O formulário agora submete diretamente via HTML.

    // 2. Função de BUSCA e PREENCHIMENTO para Edição (Mantida, mas adaptada para o fluxo)
    window.editVitima = function(id) {
        // Busca os dados da vítima via JSON (isto ainda é útil para UX)
        fetch(`/users/${id}/edit`) 
          .then(r => r.json())
          .then(data => {
            $('#edit_vitima_name').val(data.name);
            $('#edit_vitima_telefone').val(data.telefone);
            $('#edit_vitima_email').val(data.email);

            // MUDANÇA CRÍTICA: Define a action do formulário para o PUT no Laravel
            // A rota deve ser 'users.update' (que aponta para PUT /users/{id})
            $('#formEditarVitima').attr('action', `/users/${id}`); 
            $('#editVitimaModal').modal('show');
          }).catch(e => {
            // Em vez de notificações AJAX, pode usar um alert simples ou forçar um redirect para erro.
            alert('Erro ao carregar dados da vítima.');
          });
      };
      
    // 3. REMOVA O BLOCO $('#formEditarVitima').on('submit', ... )
    // O formulário agora submete diretamente via HTML.

    // 4. REMOVA TODA A LÓGICA DE EXCLUSÃO AJAX (Mantenha o confirmDelete para forms)
    // O formulário de exclusão agora é tradicional, conforme definido no Blade e no ConsultaController.

    // Apenas mantenha a função auxiliar confirmDelete (se estiver a usá-la)
    window.confirmDelete = function(actionUrl) {
        $('#deleteForm').attr('action', actionUrl);
        const modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
        modal.show();
    };

});

// REMOVA a função prependVictimRow, pois a página será recarregada.

  // Edit fetch (global function used by button onclick)
  window.editVitima = function(id) {
    fetch(`/users/${id}/edit`)
      .then(r => r.json())
      .then(data => {
        $('#edit_vitima_name').val(data.name);
        $('#edit_vitima_telefone').val(data.telefone);
        $('#edit_vitima_email').val(data.email);
        $('#formEditarVitima').attr('action', `/users/${id}`);
        $('#editVitimaModal').modal('show');
      }).catch(e => {
        showNotification('danger', 'Erro ao carregar dados.');
      });
  };

  // Submit edit
  $('#formEditarVitima').on('submit', function(e) {
    e.preventDefault();
    const action = $(this).attr('action');
    $.ajax({
      url: action,
      method: 'POST', // Laravel requires POST with _method=PUT already present in form partial
      data: $(this).serialize(),
      success: function(res) {
        $('#editVitimaModal').modal('hide');
        showNotification('success', 'Vítima atualizada.');
        // optional: refresh a single row or reload partial
        // Here, simple approach: reload page or request partial endpoint
        location.reload(); // quick and safe
      },
      error: function(xhr) {
        showNotification('danger', 'Erro ao salvar alterações.');
      }
    });
  });

      $(document).on('click', '.delete', function() {
        let id = $(this).data('id');
        if (confirm('Deseja realmente remover esta vítima?')) {
            $.ajax({
                url: '/users/' + id,
                method: 'DELETE',
                data: { _token: "{{ csrf_token() }}" },
                success: function(response) {
                    if (response.success) {
                        $('#vitima-' + id).remove();
                    } else {
                        alert(response.message);
                    }
                },
                error: function(err) {
                    alert('Erro ao apagar vítima');
                }
            });
        }
    });


  // Delete confirm helper
    window.confirmDelete = function(actionUrl) {
    $('#deleteForm').attr('action', actionUrl);
    const modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
    modal.show();
  };


      // Delete victim (AJAX, works reliably even if modal is re-rendered)
  $(document).on('submit', '#deleteForm', function(e) {
    e.preventDefault();
    const $form = $(this);
    const action = $form.attr('action');

    $.ajax({
      url: action,
      method: 'POST', // Laravel trata o _method=DELETE automaticamente
      data: $form.serialize(),
      success: function(res) {
        $('#confirmDeleteModal').modal('hide');
        showNotification('success', 'Vítima excluída com sucesso.');

        // Remove the deleted row visually
        const id = action.split('/').pop();
        $(`tr[data-id="${id}"]`).fadeOut(300, function() { $(this).remove(); });
      },
      error: function(xhr) {
        console.error(xhr.responseText);
        showNotification('danger', 'Erro ao excluir vítima.');
      }
    });
  });



  // No ficheiro vitimas.js (Ficheiro 3) - Função CORRIGIDA

function prependVictimRow(user) {
    // Nota: 'user.telefone ?? '-'' é sintaxe PHP/Laravel, em JS é 'user.telefone || '-''.
    const telefoneDisplay = user.telefone || '-'; 
    const emailDisplay = user.email || '-'; 

    const row = `<tr data-id="${user.id}">
      <td>${user.name}</td>
      <td>${telefoneDisplay}</td>
      <td>${emailDisplay}</td>
      <td class="text-center">
          <div class="d-flex" style="gap: 20px !important;">
              <button type="button" class="btn btn-outline-danger btn-sm me-2" 
                      data-bs-toggle="modal" data-bs-target="#editVitimaModal" 
                      onclick="editVitima(${user.id})">
                  <i class="bi bi-pencil-square"></i> Editar
              </button>

              <button class="btn btn-outline-dark btn-sm" 
                      onclick="confirmDelete('/users/${user.id}')">
                  <i class="bi bi-trash"></i> Excluir
              </button>
          </div>
      </td>
    </tr>`;
    
    // Adiciona a nova linha no TOPO da tabela
    $('#vitimasBody').prepend(row);
}


