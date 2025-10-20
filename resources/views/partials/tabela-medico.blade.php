<table class="table table-hover mb-0 align-middle">
    <thead class="table-light">
        <tr>
            <th>Nome</th>
            <th>Telefone</th>
            <th>Email</th>
            <th class="text-center">Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($medicos as $doutor)
        <tr>
            <td>{{ $doutor->name }}</td>
            <td>{{ $doutor->telefone }}</td>
            <td>{{ $doutor->email }}</td>
            <td class="text-center">
                <button class="btn btn-outline-danger btn-sm me-2"
                    data-bs-toggle="modal"
                    data-bs-target="#editModal"
                    onclick="editDoutor({{ $doutor->id }})">
                    <i class="bi bi-pencil-square"></i> Editar
                </button>
                <button class="btn btn-outline-dark btn-sm"
                    onclick="confirmDelete('{{ route('users.destroy', $doutor->id) }}')">
                    <i class="bi bi-trash"></i> Excluir
                </button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
