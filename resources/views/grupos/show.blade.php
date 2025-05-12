<!-- filepath: c:\laragon\www\sos-mulher\resources\views\grupos\show.blade.php -->
@extends('layouts.app')

@section('title', "Grupo: $grupo->nome")

@section('content')
<div class="container">
    <h1>{{ $grupo->nome }}</h1>
    <p>{{ $grupo->descricao }}</p>

    <h3>Usuários no Grupo</h3>
    <ul>
        @foreach ($usuarios as $usuario)
            <li>{{ $usuario->name }} ({{ $usuario->email }})</li>
        @endforeach
    </ul>

    <h3>Mensagens</h3>
    <div id="messages">
        @foreach ($mensagens as $mensagem)
            <div class="{{ $mensagem->user_id === auth()->id() ? 'sent' : 'received' }}">
                <strong>{{ $mensagem->user->name }}:</strong>
                <p>{{ $mensagem->conteudo }}</p>
            </div>
        @endforeach
    </div>

    <form action="{{ route('grupos.mensagens.send', $grupo->id) }}" method="POST">
        @csrf
        <textarea name="conteudo" placeholder="Digite sua mensagem..." required></textarea>
        <button type="submit">Enviar</button>
    </form>
</div>
@endsection