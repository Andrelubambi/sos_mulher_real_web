@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="mb-4 text-danger text-center">Meu Perfil</h2>

    {{-- Componentes de alerta --}}
    @include('components.alert')

    {{-- Formulário de atualização de perfil --}}
    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="bg-white p-4 shadow rounded">
        @csrf
        <div class="text-center mb-3">
<img src="{{ $user->profile_photo ? asset('storage/'.$user->profile_photo) : asset('vendors/images/user-default.png') }}" 
     alt="Foto de perfil" 
     class="rounded-circle" 
     width="120" 
     height="120">


    
            <div class="mt-2">
                <input type="file" name="photo" class="form-control-file">
            </div>
        </div>

        <div class="form-group">
            <label>Nome</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
        </div>

        <div class="form-group">
            <label>Telefone</label>
            <input type="text" name="telefone" class="form-control" value="{{ old('telefone', $user->telefone) }}">
        </div>

        <button type="submit" class="btn btn-danger btn-block mt-3">Salvar Alterações</button>
    </form>
   <div class="bg-white p-4 shadow rounded mt-4 text-center">
        <h4 class="text-danger mb-3">Segurança</h4>
        <p class="mb-3">Se desejar alterar sua senha, clique abaixo para redefini-la.</p>
        <a href="{{ route('password.request') }}" class="btn btn-outline-danger">
            Redefinir Senha
        </a>
    </div>
    </div>
</div>
@endsection
