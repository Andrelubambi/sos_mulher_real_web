@extends('layouts.app')
<style>
    #profilePreview {
    cursor: pointer; 
    transition: transform 0.3s ease;
}
#profilePreview:hover {
    transform: scale(1.05);
}

</style>
@section('content')
<div class="container py-5">
    <h2 class="mb-4 text-danger text-center">Meu Perfil</h2>
 
    @include('components.alert')
 
    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="bg-white p-4 shadow rounded">
        @csrf
        <div class="text-center mb-3">

            <!-- Foto de perfil -->
            <img id="profilePreview" 
                 src="{{ $user->profile_photo ? asset('storage/'.$user->profile_photo) : asset('vendors/images/user-default.png') }}"
                 alt="Foto de perfil"
                 class="profile-photo-large">

            <!-- Botão estilizado -->
            <div class="mt-3">
                <label for="photoUpload" class="btn btn-outline-danger btn-sm">
                    <i class="fas fa-camera me-1"></i> Alterar Foto
                </label>
                <input type="file" id="photoUpload" name="photo" accept="image/*" class="d-none">
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

<!-- Script para preview da imagem -->
<script>
document.getElementById('photoUpload').addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (!file) return;

    const validExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
    const fileName = file.name.toLowerCase();
    const fileExtension = fileName.substring(fileName.lastIndexOf('.') + 1);

    if (validExtensions.includes(fileExtension)) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('profilePreview').src = e.target.result;
        };
        reader.readAsDataURL(file);
    } else {
        alert('Por favor, selecione apenas uma imagem (jpg, jpeg, png, gif, bmp, webp).');
        event.target.value = ''; 
    }
});
</script>

@endsection
