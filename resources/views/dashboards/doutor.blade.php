@extends('layouts.app') {{-- Assume que você criou um layout base --}}

@section('content')
    <div class="main-container">
        <div class="xs-pd-20-10 pd-ltr-20">
            <div class="card-box pb-10">
                <div class="row pb-10">
                    <div class="col-xl-6 col-lg-6 col-md-12 mb-20">
                        <div class="card-box height-100-p widget-style3">
                            <div class="d-flex flex-wrap">
                                <div class="widget-data">
                                    <div class="weight-700 font-24 text-dark">{{ $proximasConsultas->count() }}</div>
                                    <div class="font-14 text-secondary weight-500">Próximas Consultas</div>
                                </div>
                                <div class="widget-icon">
                                    <div class="icon" data-color="#00eccf">
                                        <i class="icon-copy dw dw-calendar1"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-12 mb-20">
                        <div class="card-box height-100-p widget-style3">
                            <div class="d-flex flex-wrap">
                                <div class="widget-data">
                                    <div class="weight-700 font-24 text-dark">{{ auth()->user()->pacientes->count() }}</div>
                                    <div class="font-14 text-secondary weight-500">Total de Pacientes</div>
                                </div>
                                <div class="widget-icon">
                                    <div class="icon">
                                        <i class="icon-copy fa fa-users" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-box pb-10 mt-20">
                <div class="pd-20">
                    <h4 class="text-blue h4">Minhas Próximas Consultas</h4>
                </div>
                <div class="pb-20">
                    <table class="data-table table stripe hover nowrap">
                        <thead>
                            <tr>
                                <th>Paciente</th>
                                <th>Data</th>
                                <th>Horário</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($proximasConsultas as $consulta)
                                <tr>
                                    <td>{{ $consulta->vitima->name }}</td>
                                    <td>{{ $consulta->data }}</td>
                                    <td>{{ $consulta->horario }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection