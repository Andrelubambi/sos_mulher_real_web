<!DOCTYPE html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-id" content="{{ auth()->user()->id }}">
    <meta charset="utf-8" />
    <title>Não ao Suicídio | SOS-MULHER</title>
    <!-- Inclua todos os links de estilo do código original -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('vendors/images/apple-touch-icon.png') }}" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('vendors/images/favicon-32x32.png') }}" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('vendors/images/favicon-16x16.png') }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/core.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/icon-font.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="src/plugins/datatables/css/dataTables.bootstrap4.min.css" />
    <link rel="stylesheet" type="text/css" href="src/plugins/datatables/css/responsive.bootstrap4.min.css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/global.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/layout.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/components.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/utilities.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/pages.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/custom.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/chat.css') }}" />  
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/suicide-prevention.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/responsive.css') }}" />
     <!-- Restante dos scripts e estilos do código original -->
</head>
<body>
    <!-- Inclua o pre-loader, header e sidebar do código original aqui -->
    
    <div class="main-container">
        <div class="xs-pd-20-10 pd-ltr-20">
            <div class="hope-section">
                <h1>Você Não Está Sozinha</h1>
                <p class="lead">Sua vida tem valor imenso. Existem pessoas que se importam e querem ajudar.</p>
                <p>Lembre-se: os sentimentos difíceis são temporários, mas a esperança é permanente.</p>
            </div>

            <div class="emergency-contact">
                <h3><i class="fa fa-phone"></i> Linha de Apoio à Vida - 188</h3>
                <p>Disponível 24 horas por dia, 7 dias por semana</p>
            </div>

            <div class="card-box mb-30">
                <h4 class="h4 text-dark mb-20">Sinais de Alerta</h4>
                <div class="resources-grid">
                    <div class="resource-card">
                        <h5><i class="fa fa-exclamation-triangle"></i> Comportamentais</h5>
                        <ul>
                            <li>Isolamento social</li>
                            <li>Despedir-se de pessoas</li>
                            <li>Doar pertences</li>
                            <li>Aumento no uso de álcool/drogas</li>
                        </ul>
                    </div>
                    <div class="resource-card">
                        <h5><i class="fa fa-comment"></i> Verbais</h5>
                        <ul>
                            <li>"Não aguento mais"</li>
                            <li>"Todos estariam melhor sem mim"</li>
                            <li>"Quero desaparecer"</li>
                            <li>Falar sobre morte com frequência</li>
                        </ul>
                    </div>
                    <div class="resource-card">
                        <h5><i class="fa fa-heart"></i> Emocionais</h5>
                        <ul>
                            <li>Desesperança</li>
                            <li>Variações extremas de humor</li>
                            <li>Raiva intensa</li>
                            <li>Impulsividade</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="card-box mb-30">
                <h4 class="h4 text-dark mb-20">Estratégias de Enfrentamento</h4>
                <div class="coping-strategies">
                    <div class="strategy-item">
                        <h5>Respiração Consciente</h5>
                        <p>Respire profundamente por 4 segundos, segure por 4, expire por 4.</p>
                    </div>
                    <div class="strategy-item">
                        <h5>Conecte-se</h5>
                        <p>Ligue para alguém de confiança ou participe do nosso grupo de apoio.</p>
                    </div>
                    <div class="strategy-item">
                        <h5>Atividade Física</h5>
                        <p>Uma caminhada curta pode ajudar a liberar endorfinas.</p>
                    </div>
                    <div class="strategy-item">
                        <h5>Gratidão</h5>
                        <p>Anote três coisas pelas quais você é grata hoje.</p>
                    </div>
                </div>
            </div>

            <div class="card-box">
                <h4 class="h4 text-dark mb-20">Recursos de Apoio</h4>
                <div class="resources-grid">
                    <div class="resource-card">
                        <h5><i class="fa fa-hospital-o"></i> Serviços de Saúde</h5>
                        <p>CAPS - Centros de Atenção Psicossocial</p>
                        <p>UPA - Unidades de Pronto Atendimento</p>
                        <p>Ambulatórios de Saúde Mental</p>
                    </div>
                    <div class="resource-card">
                        <h5><i class="fa fa-group"></i> Apoio Online</h5>
                        <p>CVV - Centro de Valorização da Vida</p>
                        <p>Chat de apoio SOS Mulher</p>
                        <p>Fóruns de discussão moderados</p>
                    </div>
                    <div class="resource-card">
                        <h5><i class="fa fa-book"></i> Materiais de Apoio</h5>
                        <p>Guia de autocuidado emocional</p>
                        <p>Técnicas de mindfulness</p>
                        <p>Exercícios de regulação emocional</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
            <script src="{{ asset('vendors/scripts/core.js') }}"></script>
        <script src="{{ asset('vendors/scripts/script.min.js') }}"></script>
        <script src="{{ asset('vendors/scripts/process.js') }}"></script>
        <script src="{{ asset('vendors/scripts/layout-settings.js') }}"></script>
        <script>
            // Configuração do CSRF Token para AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        </script>
</body>
</html>