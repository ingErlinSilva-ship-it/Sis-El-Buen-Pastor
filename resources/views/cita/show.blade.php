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
<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-default">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Información de la Cita Médica</h3>
                    <div class="ml-auto">
                        <a class="btn btn-primary btn-sm" href="{{ route('cita.index') }}">
                            <i class="fas fa-arrow-left"></i> Regresar
                        </a>
                    </div>
                </div>

                <div class="card-body bg-white">
                    {{-- SECCIÓN: DATOS DEL PACIENTE --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Nombre</strong></label>
                                <input type="text" class="form-control" value="{{ $cita->paciente->usuario->nombre }}" readonly style="background-color: #bcdffb;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Apellido</strong></label>
                                <input type="text" class="form-control" value="{{ $cita->paciente->usuario->apellido }}" readonly style="background-color: #bcdffb">
                            </div>
                        </div>
                    </div>

                    {{-- SECCIÓN: MÉDICO Y ESPECIALIDAD --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Médico</strong></label>
                                <input type="text" class="form-control" value="{{ $cita->medico->usuario->nombre }} {{ $cita->medico->usuario->apellido }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Especialidad</strong></label>
                                <input type="text" class="form-control" value="{{ $cita->medico->especialidade->nombre }}" readonly >
                            </div>
                        </div>
                    </div>

                    {{-- SECCIÓN: FECHA, HORA Y ESTADO --}}
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><strong>Fecha</strong></label>
                                <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><strong>Hora</strong></label>
                                <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($cita->hora)->format('h:i A') }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><strong>Estado</strong></label>
                                {{-- Aquí usamos el mismo badge que en el index para consistencia visual --}}
                                <div>
                                    @if($cita->estado == 'pendiente')
                                        <span class="badge badge-warning text-dark p-2 w-100">PENDIENTE</span>
                                    @elseif($cita->estado == 'confirmada')
                                        <span class="badge badge-success p-2 w-100">CONFIRMADA</span>
                                    @else
                                        <span class="badge badge-danger p-2 w-100">CANCELADA</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SECCIÓN: MOTIVO --}}
                    <div class="form-group">
                        <label><strong>Motivo de la Cita</strong></label>
                        <textarea class="form-control" rows="3" readonly>{{ $cita->motivo }}</textarea>
                    </div>

                    {{-- SECCIÓN TÉCNICA (Opcional, con estilo sutil) --}}
                    <hr>
                    <div class="row">
                        <div class="col-md-4">
                            <small class="text-muted"><strong>Origen:</strong> {{ ucfirst($cita->origen) }}</small>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted"><strong>Duración:</strong> {{ $cita->duracion_minutos }} min</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
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
<style type="text/css">

    /*
    {{-- You can add AdminLTE customizations here --}}
    .card-header {
        border-bottom: none;
    }
    .card-title {
        font-weight: 600;
    }
    */

</style>
@endpush
