@extends('adminlte::page')

{{-- Extend and customize the browser title --}}

@section('title')
    {{ config('adminlte.title') }}
    @hasSection('subtitle') | @yield('subtitle') @endif
@stop

{{-- Extend and customize the page content header --}}

@section('content_header')
    @hasSection('content_header_title')
        <h1 class="text-muted">
            @yield('content_header_title')

            @hasSection('content_header_subtitle')
                <small class="text-dark">
                    <i class="fas fa-xs fa-angle-right text-muted"></i>
                    @yield('content_header_subtitle')
                </small>
            @endif
        </h1>
    @endif
@stop

{{-- Rename section content to content_body --}}

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="mb-3 d-print-none">
                <a href="{{ route('consulta.index') }}" class="btn btn-secondary shadow-sm">
                    <i class="fas fa-arrow-left"></i> Volver al Historial
                </a>
                <button onclick="window.print();" class="btn btn-primary shadow-sm float-right">
                    <i class="fas fa-print"></i> Imprimir Ficha / Receta
                </button>
            </div>

            <div class="card shadow-lg border-0">
                <div class="card-header bg-white border-bottom-0 pt-4 text-center">
                    <h2 class="mb-0"><b>CLÍNICA EL BUEN PASTOR</b></h2>
                    <p class="text-muted">Registro de Atención Médica - Diriá, Nicaragua</p>
                </div>

                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-sm-6">
                            <h5 class="text-primary border-bottom pb-1">DATOS DEL PACIENTE</h5>
                            <p class="mb-1"><strong>Nombre:</strong> {{ $consulta->paciente->usuario->nombre }} {{ $consulta->paciente->usuario->apellido }}</p>
                            <p class="mb-1"><strong>ID Cita:</strong> #{{ $consulta->cita_id }}</p>
                            <p class="mb-1"><strong>Fecha:</strong> {{ $consulta->created_at->format('d/m/Y h:i A') }}</p>
                        </div>
                        <div class="col-sm-6 text-sm-right">
                            <h5 class="text-primary border-bottom pb-1">MÉDICO TRATANTE</h5>
                            <p class="mb-1"><strong>Dr. {{ $consulta->medico->usuario->nombre }}</strong></p>
                            <p class="mb-1 text-muted">Consulta de Medicina General</p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="bg-light p-2"><i class="fas fa-heartbeat"></i> SIGNOS VITALES</h5>
                            <table class="table table-bordered text-center mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Peso</th>
                                        <th>Estatura</th>
                                        <th>P. Arterial</th>
                                        <th>Temperatura</th>
                                        <th>Frec. Cardíaca</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $consulta->peso ?? '---' }} kg</td>
                                        <td>{{ $consulta->estatura ?? '---' }} m</td>
                                        <td>{{ $consulta->presion_arterial ?? '---' }}</td>
                                        <td>{{ $consulta->temperatura ?? '---' }} °C</td>
                                        <td>{{ $consulta->frecuencia_cardiaca ?? '---' }} bpm</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <h5 class="text-primary"><i class="fas fa-stethoscope"></i> Evaluación Médica</h5>
                            <div class="p-3 border rounded bg-light">
                                <strong>Síntomas:</strong>
                                <p>{{ $consulta->sintomas }}</p>
                                <strong>Diagnóstico:</strong>
                                <p class="mb-0">{{ $consulta->diagnostico }}</p>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <h5 class="text-success"><i class="fas fa-file-prescription"></i> Prescripción / Receta</h5>
                            <div class="p-4 border border-success rounded" style="background-color: #f8fff9; min-height: 150px;">
                                {!! nl2br(e($consulta->prescripcion)) !!}
                            </div>
                        </div>
                    </div>

                    @if($consulta->observaciones)
                    <div class="row mt-3">
                        <div class="col-12">
                            <small class="text-muted"><strong>Observaciones:</strong> {{ $consulta->observaciones }}</small>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="card-footer bg-white text-center pt-5 pb-4">
                    <div class="row">
                        <div class="col-6 offset-3">
                            <div style="border-top: 1px solid #dee2e6;" class="pt-2">
                                <strong>Sello y Firma del Médico</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop


{{-- Create a common footer --}}

@section('footer')
    <div class="float-right">
        Version: {{ config('app.version', '1.0.0') }}
    </div>

    <strong>
        <a href="{{ config('app.company_url', '#') }}">
            {{ config('app.company_name', '© 2025 - Sistema web con asistente virtual para gestión de consultas médicas. Desarrollado por Levi Ruiz y Erlin Silva.') }}
        </a>
    </strong>
@stop

{{-- Add common Javascript/Jquery code --}}

@push('js')
<script>

    $(document).ready(function() {
        // Add your common script logic here...
    });

</script>
@endpush

{{-- Add common CSS customizations --}}

@push('css')
<style>
    @media print {
        .main-sidebar, .main-header, .footer { display: none !important; }
        .content-wrapper { margin-left: 0 !important; }
        .card { border: none !important; box-shadow: none !important; }
    }
</style>
@endpush

