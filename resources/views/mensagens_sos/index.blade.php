@extends('layouts.app')

@section('title', 'SOS Pendentes')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">🚨 Chamados SOS Pendentes</h2>

    @forelse ($mensagensSos as $mensagem)
    <div class="card mb-3 shadow-sm">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-title text-danger">SOS de: {{ $mensagem->remetente->name ?? 'Usuário Desconhecido' }} (ID: {{ $mensagem->enviado_por }})</h5>
                <p class="card-text">Conteúdo: {{ \Illuminate\Support\Str::limit($mensagem->conteudo, 150) }}</p>
                <p class="card-text text-muted">Recebido em: {{ \Carbon\Carbon::parse($mensagem->created_at)->format('d/m/Y H:i:s') }}</p>
            </div>
            <a href="{{ url('/responder_mensagem_sos/' . $mensagem->id) }}" class="btn btn-primary btn-responder-sos">
                Responder Chat
            </a>
        </div>
    </div>
    @empty
    <div class="alert alert-success" role="alert">
        🎉 Não há chamados SOS pendentes.
    </div>
    @endforelse

</div>
@endsection