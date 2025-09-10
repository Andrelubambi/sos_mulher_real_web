<!DOCTYPE html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-id" content="{{ auth()->user()->id }}">
    <meta charset="utf-8" />
    <title>Testemunhos | SOS-MULHER</title>
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
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/style.css') }}" />
    <style>
        .testimonial-hero {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 40px;
            border-radius: 10px;
            margin-bottom: 30px;
            text-align: center;
        }
        .testimonials-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .testimonial-card {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: relative;
        }
        .testimonial-card:before {
            content: '"';
            font-size: 60px;
            position: absolute;
            top: 10px;
            left: 15px;
            opacity: 0.1;
            font-family: Georgia, serif;
        }
        .testimonial-content {
            font-style: italic;
            margin-bottom: 15px;
            line-height: 1.6;
        }
        .testimonial-author {
            display: flex;
            align-items: center;
            margin-top: 15px;
        }
        .author-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: #667eea;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            margin-right: 15px;
        }
        .share-section {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .filter-buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        .filter-btn {
            padding: 8px 15px;
            border: 1px solid #ddd;
            border-radius: 20px;
            background: white;
            cursor: pointer;
            transition: all 0.3s;
        }
        .filter-btn.active, .filter-btn:hover {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }
    </style>
    <!-- Restante dos scripts e estilos do código original -->
</head>
<body>
    <!-- Inclua o pre-loader, header e sidebar do código original aqui -->
    
    <div class="main-container">
        <div class="xs-pd-20-10 pd-ltr-20">
            <div class="testimonial-hero">
                <h1>Histórias de Superação</h1>
                <p class="lead">Cada testemunho é uma prova de que a esperança sempre vence.</p>
                <p>Estas histórias reais mostram a força e resiliência de mulheres que enfrentaram a violência e encontraram caminhos de cura.</p>
            </div>

            <div class="filter-buttons">
                <button class="filter-btn active" data-filter="all">Todos</button>
                <button class="filter-btn" data-filter="recentes">Mais Recentes</button>
                <button class="filter-btn" data-filter="superacao">Superação</button>
                <button class="filter-btn" data-filter="apoio">Apoio Mútuo</button>
                <button class="filter-btn" data-filter="esperanca">Mensagens de Esperança</button>
            </div>

            <div class="testimonials-container">
                <!-- Testemunho 1 -->
                <div class="testimonial-card" data-category="superacao esperanca">
                    <div class="testimonial-content">
                        "Depois de anos sofrendo em silêncio, encontrar esta plataforma foi meu divisor de águas. A rede de apoio me mostrou que eu não estava sozinha e me deu forças para recomeçar. Hoje, estou reconstruindo minha vida com confiança e autoestima."
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">M</div>
                        <div>
                            <strong>Maria, 34 anos</strong>
                            <div>Há 2 meses na plataforma</div>
                        </div>
                    </div>
                </div>

                <!-- Testemunho 2 -->
                <div class="testimonial-card" data-category="apoio recentes">
                    <div class="testimonial-content">
                        "As consultas com psicólogas especializadas me ajudaram a entender que a culpa não era minha. O grupo de apoio me mostrou que outras mulheres passaram por situações similares e conseguiram se reerguer. A jornada é difícil, mas possível!"
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">A</div>
                        <div>
                            <strong>Ana, 29 anos</strong>
                            <div>Há 3 semanas na plataforma</div>
                        </div>
                    </div>
                </div>

                <!-- Testemunho 3 -->
                <div class="testimonial-card" data-category="esperanca">
                    <div class="testimonial-content">
                        "Quero dizer para todas as mulheres que ainda estão no início da caminhada: não desistam! Cada pequeno passo importa. Hoje consigo ver luz onde antes só havia escuridão, e isso só foi possível com a rede de apoio que encontrei aqui."
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">C</div>
                        <div>
                            <strong>Carla, 41 anos</strong>
                            <div>Há 6 meses na plataforma</div>
                        </div>
                    </div>
                </div>

                <!-- Testemunho 4 -->
                <div class="testimonial-card" data-category="superacao">
                    <div class="testimonial-content">
                        "O botão de SOS salvou minha vida literalmente. Naquela noite terrível, não sabia para onde correr, mas com um clique consegui acionar a ajuda que precisava. Hoje estou em um lugar seguro, reconstruindo minha vida com meu filho."
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">J</div>
                        <div>
                            <strong>Joana, 37 anos</strong>
                            <div>Há 1 ano na plataforma</div>
                        </div>
                    </div>
                </div>

                <!-- Testemunho 5 -->
                <div class="testimonial-card" data-category="apoio esperanca">
                    <div class="testimonial-content">
                        "Encontrar outras mulheres que entenderam minha dor sem julgamento foi transformador. Através dos chats de apoio, formamos amizades verdadeiras que nos fortalecem mutuamente. Juntas somos mais fortes!"
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">T</div>
                        <div>
                            <strong>Teresa, 45 anos</strong>
                            <div>Há 8 meses na plataforma</div>
                        </div>
                    </div>
                </div>

                <!-- Testemunho 6 -->
                <div class="testimonial-card" data-category="recentes superacao">
                    <div class="testimonial-content">
                        "Há três meses eu não me reconhecia no espelho. Hoje, com a ajuda das terapeutas e do grupo, estou redescobrindo minha força e minha voz. A violência tentou calar minha essência, mas falhou. Estou renascendo!"
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">R</div>
                        <div>
                            <strong>Rita, 32 anos</strong>
                            <div>Há 3 meses na plataforma</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="share-section">
                <h4 class="h4 text-dark mb-20">Compartilhe Sua História</h4>
                <p>Suas experiências podem inspirar e fortalecer outras mulheres. Se você se sente confortável para compartilhar sua jornada, sua voz pode ser a luz que guia alguém para a esperança.</p>
                <p>Os testemunhos são anônimos e você controla quanto quer revelar.</p>
                <button class="btn btn-primary">Compartilhar Minha Experiência</button>
            </div>

            <div class="card-box">
                <h4 class="h4 text-dark mb-20">Recursos Baseados nas Experiências</h4>
                <div class="resources-grid">
                    <div class="resource-card">
                        <h5><i class="fa fa-heart"></i> Guia de Autocuidado</h5>
                        <p>Práticas recomendadas por mulheres que passaram pela jornada de cura.</p>
                    </div>
                    <div class="resource-card">
                        <h5><i class="fa fa-group"></i> Grupos de Apoio</h5>
                        <p>Conecte-se com outras mulheres em diferentes estágios de recuperação.</p>
                    </div>
                    <div class="resource-card">
                        <h5><i class="fa fa-book"></i> Diário da Jornada</h5>
                        <p>Um espaço para registrar progressos e reflexões ao longo do processo.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Inclua os scripts do código original aqui -->
    <script>
        // Filtro para os testemunhos
        document.addEventListener('DOMContentLoaded', function() {
            const filterButtons = document.querySelectorAll('.filter-btn');
            const testimonialCards = document.querySelectorAll('.testimonial-card');
            
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Remove a classe active de todos os botões
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    // Adiciona a classe active ao botão clicado
                    this.classList.add('active');
                    
                    const filter = this.getAttribute('data-filter');
                    
                    testimonialCards.forEach(card => {
                        if (filter === 'all') {
                            card.style.display = 'block';
                        } else {
                            const categories = card.getAttribute('data-category').split(' ');
                            if (categories.includes(filter)) {
                                card.style.display = 'block';
                            } else {
                                card.style.display = 'none';
                            }
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>