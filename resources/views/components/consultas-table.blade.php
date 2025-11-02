@props(['consultas', 'class' => ''])

<div class="{{ $class }}">
    <div class="card-box">
        <h5 class="h5 text-dark mb-20 pl-20 mt-4">Lista de Consultas</h5>
        <input type="text" id="searchConsulta" class="form-control mb-3" placeholder="Pesquisar por vítima ou médico">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nome da Vítima</th>
                        <th>Médico</th> 
                        <th>Data</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="consultaTableBody">
                    @foreach ($consultas as $consulta)
                        <tr>
                            <td>{{ $consulta->id }}</td>
                            <td>{{ $consulta->vitima->nome ?? 'N/A' }}</td>
                            <td>{{ $consulta->medico->name ?? 'N/A' }}</td>
                            <td>{{ $consulta->created_at->format('d/m/Y') }}</td>
                            <td>{{ $consulta->status ?? 'Marcada' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>