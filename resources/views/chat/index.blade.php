

<x-chat-shell 
    :chats-recentes="$chatsRecentes" 
    :usuarios-nao-doutores="$usuariosNaoDoutores"
    :initial-chat-user-id="$initialChatUserId ?? null"
    title="Chat | Mensagens SOS-MULHER"
>
    {{-- O conteúdo do $slot agora é a área de chat principal --}}
    <x-chat-main-area /> 

</x-chat-shell> 