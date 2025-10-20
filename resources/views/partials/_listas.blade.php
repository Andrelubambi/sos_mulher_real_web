<div class="row">
    <!-- Lista de Doutores -->
    <div class="col-md-6 mb-30">
        <div class="card-box">
            <h5 class="h5 text-dark mb-20 p-3">Lista de Doutores</h5>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($doutores as $doutor)
                            <tr>
                                <td>{{ $doutor->name }}</td>
                                <td>{{ $doutor->email }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted">Nenhum doutor encontrado</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Lista de Estagiários -->
    <div class="col-md-6 mb-30">
        <div class="card-box">
            <h5 class="h5 text-dark mb-20 p-3">Lista de Estagiários</h5>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($estagiarios as $estagiario)
                            <tr>
                                <td>{{ $estagiario->name }}</td>
                                <td>{{ $estagiario->email }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted">Nenhum estagiário encontrado</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Lista de Vítimas -->
    <div class="col-md-6 mb-30">
        <div class="card-box">
            <h5 class="h5 text-dark mb-20 p-3">Lista de Vítimas</h5>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($vitimas as $vitima)
                            <tr>
                                <td>{{ $vitima->name }}</td>
                                <td>{{ $vitima->email }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted">Nenhuma vítima encontrada</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Consultas Marcadas -->
    <div class="col-md-6 mb-30">
        <div class="card-box">
            <h5 class="h5 text-dark mb-20 p-3">Consultas Marcadas</h5>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Status</th>
                            <th>Médico</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($consultasMarcadas as $consulta)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($consulta->created_at)->format('d/m/Y') }}</td>
                                <td>{{ ucfirst($consulta->status) }}</td>
                                <td>{{ $consulta->medico->name ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">Nenhuma consulta marcada</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
