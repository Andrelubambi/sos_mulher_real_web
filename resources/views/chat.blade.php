<!DOCTYPE html>
<html>
	<head>
		<!-- Basic Page Info -->
		<meta charset="utf-8" />
		<title>DeskApp - Bootstrap Admin Dashboard HTML Template</title>

		<meta name="csrf-token" content="{{ csrf_token() }}">
		@vite('resources/js/app.js')

		<!-- Site favicon -->
		<link
			rel="apple-touch-icon"
			sizes="180x180"
			href="vendors/images/apple-touch-icon.png"
		/>
		<link
			rel="icon"
			type="image/png"
			sizes="32x32"
			href="vendors/images/favicon-32x32.png"
		/>
		<link
			rel="icon"
			type="image/png"
			sizes="16x16"
			href="vendors/images/favicon-16x16.png"
		/>

		<!-- Mobile Specific Metas -->
		<meta
			name="viewport"
			content="width=device-width, initial-scale=1, maximum-scale=1"
		/>

		<!-- Google Font -->
		<link
			href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
			rel="stylesheet"
		/>
		<!-- CSS -->
		<link rel="stylesheet" type="text/css" href="vendors/styles/core.css" />
		<link
			rel="stylesheet"
			type="text/css"
			href="vendors/styles/icon-font.min.css"
		/>
		<link rel="stylesheet" type="text/css" href="vendors/styles/style.css" />

		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script
			async
			src="https://www.googletagmanager.com/gtag/js?id=G-GBZ3SGGX85"
		></script>
		<script
			async
			src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2973766580778258"
			crossorigin="anonymous"
		></script>
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag() {
				dataLayer.push(arguments);
			}
			gtag("js", new Date());

			gtag("config", "G-GBZ3SGGX85");
		</script>
		<!-- Google Tag Manager -->
		<script>
			(function (w, d, s, l, i) {
				w[l] = w[l] || [];
				w[l].push({ "gtm.start": new Date().getTime(), event: "gtm.js" });
				var f = d.getElementsByTagName(s)[0],
					j = d.createElement(s),
					dl = l != "dataLayer" ? "&l=" + l : "";
				j.async = true;
				j.src = "https://www.googletagmanager.com/gtm.js?id=" + i + dl;
				f.parentNode.insertBefore(j, f);
			})(window, document, "script", "dataLayer", "GTM-NXZMQSS");
		</script>
		<!-- End Google Tag Manager -->
	</head>
	<body>
		 

		 

		<div class="left-side-bar">
			 
			<div class="menu-block customscroll">
				<div class="sidebar-menu">
					<ul id="accordion-menu">
						<li class="dropdown">
							<a href="javascript:;" class="dropdown-toggle">
								<span class="micon bi bi-house"></span
								><span class="mtext">Home</span>
							</a>
							<ul class="submenu">
								<li><a href="index.html">Dashboard style 1</a></li>
								<li><a href="index2.html">Dashboard style 2</a></li>
								<li><a href="index3.html">Dashboard style 3</a></li>
							</ul>
						</li>
						
					</ul>
				</div>
			</div>
		</div>
		<div class="mobile-menu-overlay"></div>

		<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header">
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<div class="title">
									<h4>Chat</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="index.html">Home</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Chat
										</li>
									</ol>
								</nav>
							</div>
						</div>
					</div>

<div class="bg-white border-radius-4 box-shadow mb-30">
    <div class="row no-gutters">
        <div class="col-lg-3 col-md-4 col-sm-12">
            <div class="chat-list bg-light-gray">
                <div class="chat-search">
                    <span class="ti-search"></span>
                    <input type="text" placeholder="Search Contact" />
                </div>
                <div class="notification-list chat-notification-list customscroll">
                    <ul>
						@foreach($usuariosNaoDoutores ?? [] as $usuario)
						<li>
							<a href="{{ route('chat.withUser', ['usuarioId' => $usuario->id]) }}">
								<img src="vendors/images/profile-photo.jpg" alt="" />
								<h3 class="clearfix">{{ $usuario->name }}</h3>
								<p>
									<i class="fa fa-circle text-light-green"></i> {{ $usuario->role }}
								</p>
							</a>
						</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-9 col-md-8 col-sm-12">
			@if(isset($usuario))
            <div class="chat-detail">
                <div class="chat-profile-header clearfix">
                    <div class="left">
                        <div class="clearfix">
                            <div class="chat-profile-photo">
                                <img src="vendors/images/profile-photo.jpg" alt="" />
                            </div>
                            <div class="chat-profile-name">
    <h3>{{ $usuario->name }}</h3>
    <span>{{ $usuario->role }}</span>
</div>

                        </div>
                    </div>
                </div>
				<div class="chat-box">
                    <div class="chat-desc customscroll">
                        @forelse($messages as $message)
                            <div class="message mb-2">
                                <strong>{{ $message->remetente->name }}:</strong>
                                {{ $message->conteudo }}
                            </div>
                        @empty
                            <div class="text-muted">Nenhuma mensagem ainda.</div>
                        @endforelse
                    </div>
					<div class="chat-footer">
                        <div class="file-upload">
                            <a href="#"><i class="fa fa-paperclip"></i></a>
                        </div>
                        <form action="{{ route('sendMessage', $usuario->id) }}" method="POST">
                            @csrf
                            <div class="chat_text_area">
                                <textarea name="conteudo" placeholder="Type your message…" required></textarea>
                            </div>
                            <div class="chat_send">
                                <button class="btn btn-link" type="submit">
                                    <i class="icon-copy ion-paper-airplane"></i>
                                </button>
                            </div>
                         </form>
                </div>
                @else
                    <div class="p-4">
                        <h4>Selecione um usuário para iniciar a conversa.</h4>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

				</div>
				
			</div>
		</div>
		<!-- welcome modal start -->
	 
	 
		<!-- js -->

		<script src="vendors/scripts/core.js"></script>
		<script src="vendors/scripts/script.min.js"></script>
		<script src="vendors/scripts/process.js"></script>
		<script src="vendors/scripts/layout-settings.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const userId = {{ auth()->user()->id }}; // ou defina de outro jeito
        Echo.private('chat.' + userId)
            .listen('MessageSent', (e) => {
                console.log('Mensagem recebida:', e);

                const chatBox = document.querySelector('.chat-desc');
                const messageDiv = document.createElement('div');
                messageDiv.classList.add('message', 'mb-2');
                messageDiv.innerHTML = `<strong>${e.remetente.name}:</strong> ${e.conteudo}`;
                chatBox.appendChild(messageDiv);

                chatBox.scrollTop = chatBox.scrollHeight;
            });
    });
</script>

		<!-- Google Tag Manager (noscript) -->
		<noscript
			><iframe
				src="https://www.googletagmanager.com/ns.html?id=GTM-NXZMQSS"
				height="0"
				width="0"
				style="display: none; visibility: hidden"
			></iframe
		></noscript>
		<!-- End Google Tag Manager (noscript) -->
	</body>
</html>
