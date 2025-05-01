<!DOCTYPE html>
<html>
	<head>
		<!-- Basic Page Info -->
		<meta charset="utf-8" />
		<title>DeskApp - Bootstrap Admin Dashboard HTML Template</title>

		<!-- Site favicon -->
		<link
	rel="apple-touch-icon"
	sizes="180x180"
	href="{{ asset('vendors/images/apple-touch-icon.png') }}"
/>

<link
rel="icon"
type="image/png"
sizes="32x32"
href="{{ asset('vendors/images/favicon-32x32.png') }}"
/>

<link
rel="icon"
type="image/png"
sizes="16x16"
href="{{ asset('vendors/images/favicon-16x16.png') }}"
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
		<link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/core.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/icon-font.min.css') }}" />

		<link
			rel="stylesheet"
			type="text/css"
			href="src/plugins/datatables/css/dataTables.bootstrap4.min.css"
		/>
		<link
			rel="stylesheet"
			type="text/css"
			href="src/plugins/datatables/css/responsive.bootstrap4.min.css"
		/>
		<link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/style.css') }}" />


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
		<div class="pre-loader">
			<div class="pre-loader-box">
				<div class="loader-logo">
					<img src="{{ asset('vendors/images/deskapp-logo.svg') }}" alt="" />

				</div>
				<div class="loader-progress" id="progress_div">
					<div class="bar" id="bar1"></div>
				</div>
				<div class="percent" id="percent1">0%</div>
				<div class="loading-text">Loading...</div>
			</div>
		</div>

		<div class="header">
			<div class="header-left">
				<div class="menu-icon bi bi-list"></div>
				<div
					class="search-toggle-icon bi bi-search"
					data-toggle="header_search"
				></div>
				<div class="header-search">
					<form>
						<div class="form-group mb-0">
							<i class="dw dw-search2 search-icon"></i>
							<input
								type="text"
								class="form-control search-input"
								placeholder="Search Here"
							/>
							<div class="dropdown">
								<a
									class="dropdown-toggle no-arrow"
									href="#"
									role="button"
									data-toggle="dropdown"
								>
									<i class="ion-arrow-down-c"></i>
								</a>
								<div class="dropdown-menu dropdown-menu-right">
									<div class="form-group row">
										<label class="col-sm-12 col-md-2 col-form-label"
											>From</label
										>
										<div class="col-sm-12 col-md-10">
											<input
												class="form-control form-control-sm form-control-line"
												type="text"
											/>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-12 col-md-2 col-form-label">To</label>
										<div class="col-sm-12 col-md-10">
											<input
												class="form-control form-control-sm form-control-line"
												type="text"
											/>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-12 col-md-2 col-form-label"
											>Subject</label
										>
										<div class="col-sm-12 col-md-10">
											<input
												class="form-control form-control-sm form-control-line"
												type="text"
											/>
										</div>
									</div>
									<div class="text-right">
										<button class="btn btn-primary">Search</button>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
			<div class="header-right">
				<div class="dashboard-setting user-notification">
					<div class="dropdown">
						<a
							class="dropdown-toggle no-arrow"
							href="javascript:;"
							data-toggle="right-sidebar"
						>
							<i class="dw dw-settings2"></i>
						</a>
					</div>
				</div>
				<div class="user-notification">
					<div class="dropdown">
						<a
							class="dropdown-toggle no-arrow"
							href="#"
							role="button"
							data-toggle="dropdown"
						>
							<i class="icon-copy dw dw-notification"></i>
							<span class="badge notification-active"></span>
						</a>
						<div class="dropdown-menu dropdown-menu-right">
							<div class="notification-list mx-h-350 customscroll">
								<ul>
									<li>
										<a href="#">
											<img src="{{ asset('vendors/images/img.jpg') }}" alt="" />
											<h3>John Doe</h3>
											<p>
												Lorem ipsum dolor sit amet, consectetur adipisicing
												elit, sed...
											</p>
										</a>
										
									</li>
									<li>
										<a href="#">
											<img src="{{ asset('vendors/images/photo1.jpg') }}" alt="" />
											<h3>Lea R. Frith</h3>
											<p>
												Lorem ipsum dolor sit amet, consectetur adipisicing
												elit, sed...
											</p>
										</a>
									</li>
									
									<li>
										<a href="#">
											<img src="{{ asset('vendors/images/photo2.jpg') }}" alt="" />
											<h3>Erik L. Richards</h3>
											<p>
												Lorem ipsum dolor sit amet, consectetur adipisicing
												elit, sed...
											</p>
										</a>
									</li>
									
									<li>
										<a href="#">
											<img src="{{ asset('vendors/images/photo3.jpg') }}" alt="" />
											<h3>John Doe</h3>
											<p>
												Lorem ipsum dolor sit amet, consectetur adipisicing
												elit, sed...
											</p>
										</a>
									</li>
									<li>
										<a href="#">
											<img src="{{ asset('vendors/images/photo4.jpg') }}" alt="" />
											<h3>Renee I. Hansen</h3>
											<p>
												Lorem ipsum dolor sit amet, consectetur adipisicing
												elit, sed...
											</p>
										</a>
									</li>
									<li>
										<a href="#">
											<img src="{{ asset('vendors/images/img.jpg') }}" alt="" />
											<h3>Vicki M. Coleman</h3>
											<p>
												Lorem ipsum dolor sit amet, consectetur adipisicing
												elit, sed...
											</p>
										</a>
									</li>
									
								</ul>
							</div>
						</div>
					</div>
				</div>
				<div class="user-info-dropdown">
					<div class="dropdown">
						<a
							class="dropdown-toggle"
							href="#"
							role="button"
							data-toggle="dropdown"
						>
						<span class="user-icon">
							<img src="{{ asset('vendors/images/photo1.jpg') }}" alt="" />
						</span>
						
							<span class="user-name">Ross C. Lopez</span>
						</a>
						<div
							class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list"
						>
							<a class="dropdown-item" href="profile.html"
								><i class="dw dw-user1"></i> Profile</a
							>
							<a class="dropdown-item" href="profile.html"
								><i class="dw dw-settings2"></i> Setting</a
							>
							<a class="dropdown-item" href="faq.html"
								><i class="dw dw-help"></i> Help</a
							>
							<a class="dropdown-item" href="login.html"
								><i class="dw dw-logout"></i> Log Out</a
							>
						</div>
					</div>
				</div>
				<div class="github-link">
					<a href="https://github.com/dropways/deskapp" target="_blank"
						><img src="vendors/images/github.svg" alt=""
					/></a>
				</div>
			</div>
		</div>

		<div class="right-sidebar">
			<div class="sidebar-title">
				<h3 class="weight-600 font-16 text-blue">
					Layout Settings
					<span class="btn-block font-weight-400 font-12"
						>User Interface Settings</span
					>
				</h3>
				<div class="close-sidebar" data-toggle="right-sidebar-close">
					<i class="icon-copy ion-close-round"></i>
				</div>
			</div>
			<div class="right-sidebar-body customscroll">
				<div class="right-sidebar-body-content">
					<h4 class="weight-600 font-18 pb-10">Header Background</h4>
					<div class="sidebar-btn-group pb-30 mb-10">
						<a
							href="javascript:void(0);"
							class="btn btn-outline-primary header-white active"
							>White</a
						>
						<a
							href="javascript:void(0);"
							class="btn btn-outline-primary header-dark"
							>Dark</a
						>
					</div>

					<h4 class="weight-600 font-18 pb-10">Sidebar Background</h4>
					<div class="sidebar-btn-group pb-30 mb-10">
						<a
							href="javascript:void(0);"
							class="btn btn-outline-primary sidebar-light"
							>White</a
						>
						<a
							href="javascript:void(0);"
							class="btn btn-outline-primary sidebar-dark active"
							>Dark</a
						>
					</div>

					<h4 class="weight-600 font-18 pb-10">Menu Dropdown Icon</h4>
					<div class="sidebar-radio-group pb-10 mb-10">
						<div class="custom-control custom-radio custom-control-inline">
							<input
								type="radio"
								id="sidebaricon-1"
								name="menu-dropdown-icon"
								class="custom-control-input"
								value="icon-style-1"
								checked=""
							/>
							<label class="custom-control-label" for="sidebaricon-1"
								><i class="fa fa-angle-down"></i
							></label>
						</div>
						<div class="custom-control custom-radio custom-control-inline">
							<input
								type="radio"
								id="sidebaricon-2"
								name="menu-dropdown-icon"
								class="custom-control-input"
								value="icon-style-2"
							/>
							<label class="custom-control-label" for="sidebaricon-2"
								><i class="ion-plus-round"></i
							></label>
						</div>
						<div class="custom-control custom-radio custom-control-inline">
							<input
								type="radio"
								id="sidebaricon-3"
								name="menu-dropdown-icon"
								class="custom-control-input"
								value="icon-style-3"
							/>
							<label class="custom-control-label" for="sidebaricon-3"
								><i class="fa fa-angle-double-right"></i
							></label>
						</div>
					</div>

					<h4 class="weight-600 font-18 pb-10">Menu List Icon</h4>
					<div class="sidebar-radio-group pb-30 mb-10">
						<div class="custom-control custom-radio custom-control-inline">
							<input
								type="radio"
								id="sidebariconlist-1"
								name="menu-list-icon"
								class="custom-control-input"
								value="icon-list-style-1"
								checked=""
							/>
							<label class="custom-control-label" for="sidebariconlist-1"
								><i class="ion-minus-round"></i
							></label>
						</div>
						<div class="custom-control custom-radio custom-control-inline">
							<input
								type="radio"
								id="sidebariconlist-2"
								name="menu-list-icon"
								class="custom-control-input"
								value="icon-list-style-2"
							/>
							<label class="custom-control-label" for="sidebariconlist-2"
								><i class="fa fa-circle-o" aria-hidden="true"></i
							></label>
						</div>
						<div class="custom-control custom-radio custom-control-inline">
							<input
								type="radio"
								id="sidebariconlist-3"
								name="menu-list-icon"
								class="custom-control-input"
								value="icon-list-style-3"
							/>
							<label class="custom-control-label" for="sidebariconlist-3"
								><i class="dw dw-check"></i
							></label>
						</div>
						<div class="custom-control custom-radio custom-control-inline">
							<input
								type="radio"
								id="sidebariconlist-4"
								name="menu-list-icon"
								class="custom-control-input"
								value="icon-list-style-4"
								checked=""
							/>
							<label class="custom-control-label" for="sidebariconlist-4"
								><i class="icon-copy dw dw-next-2"></i
							></label>
						</div>
						<div class="custom-control custom-radio custom-control-inline">
							<input
								type="radio"
								id="sidebariconlist-5"
								name="menu-list-icon"
								class="custom-control-input"
								value="icon-list-style-5"
							/>
							<label class="custom-control-label" for="sidebariconlist-5"
								><i class="dw dw-fast-forward-1"></i
							></label>
						</div>
						<div class="custom-control custom-radio custom-control-inline">
							<input
								type="radio"
								id="sidebariconlist-6"
								name="menu-list-icon"
								class="custom-control-input"
								value="icon-list-style-6"
							/>
							<label class="custom-control-label" for="sidebariconlist-6"
								><i class="dw dw-next"></i
							></label>
						</div>
					</div>

					<div class="reset-options pt-30 text-center">
						<button class="btn btn-danger" id="reset-settings">
							Reset Settings
						</button>
					</div>
				</div>
			</div>
		</div>

		<div class="left-side-bar">
			<div class="brand-logo">
				<a href="{{ route('index') }}">
					<img src="{{ asset('vendors/images/deskapp-logo.svg') }}" alt="" class="dark-logo" />
					<img src="{{ asset('vendors/images/deskapp-logo-white.svg') }}" alt="" class="light-logo" />
				</a>
				
				<div class="close-sidebar" data-toggle="left-sidebar-close">
					<i class="ion-close-round"></i>
				</div>
			</div>
			<div class="menu-block customscroll">
				<div class="sidebar-menu">
					<ul id="accordion-menu">
						<li class="dropdown">
							<a href="javascript:;" class="dropdown-toggle">
								<span class="micon bi bi-house"></span
								><span class="mtext">Home</span>
							</a>
							<ul class="submenu">
								<li><a href="{{ route('index') }}">Dashboard Médico</a></li>
								<li><a href="{{ route('index3') }}">Dashboard Administrador</a></li>

							</ul>
						</li>


						<li class="dropdown">
							<a href="javascript:;" class="dropdown-toggle">
								<span class="micon bi bi-house"></span
								><span class="mtext">Médico</span>
							</a>
							<ul class="submenu">
								<li><a href="{{ route('users.doutor') }}">Médico</a></li>
							</ul>
						</li>

						<li class="dropdown">
							<a href="javascript:;" class="dropdown-toggle">
								<span class="micon bi bi-house"></span
								><span class="mtext">Vítimas</span>
							</a>
							<ul class="submenu">
								<li><a href="{{ route('users.vitima') }}">Vítimas</a>								</li>
							</ul>
						</li>

						<li class="dropdown">
							<a href="javascript:;" class="dropdown-toggle">
								<span class="micon bi bi-house"></span
								><span class="mtext">Assistntes</span>
							</a>
							<ul class="submenu">
								<li><a href="{{ route('users.estagiario') }}">Assistentes</a></li>
							</ul>
						</li>

						<li class="dropdown">
							<a href="javascript:;" class="dropdown-toggle">
								<span class="micon bi bi-house"></span
								><span class="mtext">Consultas</span>
							</a>
							<ul class="submenu">
								<li><a href="{{ route('consulta') }}">Consultas</a></li>
							</ul>
						</li>
						
						<li>
							<a href="{{ route('chat') }}" class="dropdown-toggle no-arrow">
								<span class="micon bi bi-chat-right-dots"></span>
								<span class="mtext">Chat</span>
							</a>
							
						</li>
						
						<li>
							<div class="dropdown-divider"></div>
						</li>
						<li>
							<div class="sidebar-small-cap">Extra</div>
						</li>
						<li>
							<a href="javascript:;" class="dropdown-toggle">
								<span class="micon bi bi-file-pdf"></span
								><span class="mtext">Documentation</span>
							</a>
							<ul class="submenu">
								<li><a href="introduction.html">Introduction</a></li>
								<li><a href="getting-started.html">Getting Started</a></li>
								<li><a href="color-settings.html">Color Settings</a></li>
								<li>
									<a href="third-party-plugins.html">Third Party Plugins</a>
								</li>
							</ul>
						</li>
						<li>
							<a
								href="https://dropways.github.io/deskapp-free-single-page-website-template/"
								target="_blank"
								class="dropdown-toggle no-arrow"
							>
							<span class="micon bi bi-layout-text-window-reverse"></span>
							<span class="mtext">
								Landing Page
								<img src="{{ asset('vendors/images/coming-soon.png') }}" alt="" width="25" />
							</span>
							
							</a>
						</li>
					</ul>
				</ul>

				</div>
			</div>
		</div>
		<div class="mobile-menu-overlay"></div>

		<div class="main-container">
			<div class="xs-pd-20-10 pd-ltr-20">
				<div class="title pb-20">
					<h2 class="h3 mb-0">Gerir Consultas</h2>
				</div>

				<div class="card-box pb-10">
					<div class="h5 pd-20 mb-0">Consultas Recente</div>
				
					<!-- Botão -->
					<button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalAdicionarConsulta">
						Adicionar Consulta
					</button>
				
			 
				<!-- Tabela de Consulta -->
<table class="table">
    <thead>
        <tr>
            <th>Descrição</th>
            <th>Bairro</th>
            <th>Província</th>
            <th>Data</th>
            <th>Médico</th>
            <th>Criado por</th>
            <th>Ações</th> 
        </tr>
    </thead>
	<tbody>
    @forelse($consultas as $consulta)
        <tr>
            <td>{{ $consulta->descricao }}</td>
            <td>{{ $consulta->bairro }}</td>
            <td>{{ $consulta->provincia }}</td>
            <td>{{ $consulta->data }}</td>
            <td>{{ $consulta->medico->name ?? 'N/A' }}</td>
            <td>{{ $consulta->criador->name ?? 'N/A' }}</td>
            <td>
                <div class="d-flex gap-2">
                    <button 
                        type="button" 
                        class="btn btn-primary btn-sm" 
                        data-toggle="modal" 
                        data-target="#editModal" 
                        onclick="editConsulta({{ $consulta->id }})">
                        <i class="bi bi-pencil-square"></i> Editar
                    </button>
                    
                    <form action="{{ route('consulta.destroy', $consulta->id) }}" method="POST">
												@csrf
												@method('DELETE')
												<button 
													type="submit" 
													class="btn btn-danger btn-sm d-flex align-items-center gap-1"
												>
													<i class="bi bi-trash"></i> Excluir
												</button>
											</form>
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="7" class="text-center">Nenhuma consulta encontrada.</td>
        </tr>
    @endforelse
</tbody>

</table>
				</div>
				
				<!-- Modal Criar Consulta -->
<div class="modal fade" id="modalAdicionarConsulta" tabindex="-1" aria-labelledby="modalAdicionarConsultaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('consulta.store') }}" class="modal-content shadow rounded">
            @csrf

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalAdicionarConsultaLabel">Nova Consulta</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label for="descricao" class="form-label">Descrição</label>
                    <input type="text" name="descricao" id="descricao" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="bairro" class="form-label">Bairro</label>
                    <input type="text" name="bairro" id="bairro" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="provincia" class="form-label">Província</label>
                    <input type="text" name="provincia" id="provincia" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="data" class="form-label">Data</label>
                    <input type="date" name="data" id="data" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="medico_id" class="form-label">Médico</label>
                    <select name="medico_id" id="medico_id" class="form-select" required>
                        <option value="">Selecione um médico</option>
                        @foreach ($medicos as $medico)
                            <option value="{{ $medico->id }}">{{ $medico->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Criar Consulta</button>
            </div>
        </form>
    </div>
</div>

				<!-- Modal Editar Consulta -->

<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="editForm" method="POST" action="" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Editar Consulta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="descricao">Descrição</label>
                    <input type="text" class="form-control" id="descricao" name="descricao" required>
                </div>
                <div class="mb-3">
                    <label for="bairro">Bairro</label>
                    <input type="text" class="form-control" id="bairro" name="bairro" required>
                </div>
                <div class="mb-3">
                    <label for="provincia">Província</label>
                    <input type="text" class="form-control" id="provincia" name="provincia" required>
                </div>
                <div class="mb-3">
                    <label for="data">Data</label>
                    <input type="date" class="form-control" id="data" name="data" required>
                </div>
                <div class="mb-3">
                    <label for="medico_id">Médico</label>
                    <select name="medico_id" id="medico_id" class="form-control" required>
                        @foreach ($medicos as $medico)
                            <option value="{{ $medico->id }}">{{ $medico->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Salvar alterações</button>
            </div>
        </form>
    </div>
</div>


	<!-- Script para Editar Consulta -->
<script>
    function editConsulta(id) {
        fetch(`/consultas/${id}/edit`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('descricao').value = data.descricao;
                document.getElementById('bairro').value = data.bairro;
                document.getElementById('provincia').value = data.provincia;
                document.getElementById('data').value = data.data;
                document.getElementById('medico_id').value = data.medico_id;
                document.getElementById('editForm').action = `/consultas/${id}`;
            });
    }
</script>

				
				<script>
					function editConsulta(id) {
						fetch(`/users/${id}/edit`)
							.then(response => response.json())
							.then(data => {
								document.getElementById('name').value = data.name;
								document.getElementById('telefone').value = data.telefone;
								document.getElementById('editForm').action = `/users/${id}`;
							});
					}
				</script>
				
</div>

<script src="{{ asset('vendors/scripts/core.js') }}"></script>
<script src="{{ asset('vendors/scripts/script.min.js') }}"></script> 
<script src="{{ asset('vendors/scripts/process.js') }}"></script>
<script src="{{ asset('vendors/scripts/layout-settings.js') }}"></script>
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
		<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

	</body>
</html>
