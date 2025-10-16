@include('layouts.app')

<div class="container p-4">
    <div class="row">
        <div class="col-md-6">
            <div class="card-box p-3">
                <h4>Dados pessoais</h4>
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                <form method="POST" action="{{ route('users.update', auth()->id()) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ auth()->user()->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="telefone" class="form-label">Telefone</label>
                        <input type="tel" pattern="[0-9]+" inputmode="numeric" class="form-control" id="telefone" name="telefone" value="{{ auth()->user()->telefone }}" required>
                    </div>
                    <button type="submit" class="btn btn-danger">Salvar</button>
                </form>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card-box p-3">
                <h4>Alterar senha</h4>
                <form method="POST" action="{{ route('users.update_password', auth()->id()) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Senha atual (opcional)</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" placeholder="Senha atual">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Nova senha</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirmar nova senha</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                    <button type="submit" class="btn btn-danger">Atualizar senha</button>
                </form>
            </div>
        </div>
    </div>
</div>


