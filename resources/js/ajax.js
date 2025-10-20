function editEstagiario(id) {
    $.get(`/users/${id}/edit`, function (data) {
        $('#name').val(data.name);
        $('#telefone').val(data.telefone);
        $('#editForm').attr('action', `/users/${id}`);
        $('#editModal').modal('show');
    });
}
