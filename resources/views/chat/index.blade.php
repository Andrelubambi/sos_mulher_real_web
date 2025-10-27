{{-- ARQUIVO: resources/views/chat/index.blade.php --}}

{{-- ATENÇÃO: Removemos @extends('layouts.app') e @section('content') --}}
{{-- O x-chat-shell agora é o layout principal --}}

<x-chat-shell 
    :chats-recentes="$chatsRecentes" 
    :usuarios-nao-doutores="$usuariosNaoDoutores"
    title="Chat | Mensagens SOS-MULHER"
>
    {{-- O conteúdo do $slot agora é a área de chat principal --}}
    <x-chat-main-area /> 

</x-chat-shell>