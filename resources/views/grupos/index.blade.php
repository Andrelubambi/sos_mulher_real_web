<!-- filepath: c:\laragon\www\sos-mulher\resources\views\grupos\index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Grupos</h1>
    <ul>
        @foreach ($grupos as $grupo)
            <li>
                <a href="{{ route('grupos.show', $grupo->id) }}">{{ $grupo->nome }}</a>
                <p>{{ $grupo->descricao }}</p>
                <p>Admin: {{ $grupo->admin->name }}</p>
            </li>
        @endforeach
    </ul>
</div>
@endsection