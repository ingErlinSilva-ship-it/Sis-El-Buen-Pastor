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
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">Información del Paciente</span>
                        </div>
                        <div class="ml-auto">
                            <a class="btn btn-primary btn-sm" href="{{ route('paciente.index') }}">
                                <i class="fas fa-arrow-left"></i> Regresar
                            </a>
                        </div>
                    </div>

                    <div class="card-body bg-white">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><strong>Nombre</strong></label>
                                    <input type="text" class="form-control" value="{{ $paciente->usuario->nombre }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><strong>Apellido</strong></label>
                                    <input type="text" class="form-control" value="{{ $paciente->usuario->apellido }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><strong>Fecha de Nacimiento</strong></label>
                                    <input type="text" class="form-control" value="{{  \Carbon\Carbon::parse($paciente->fecha_nacimiento)->format('d/m/Y') }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><strong>Edad Actual</strong></label>
                                    <input type="text" class="form-control" 
                                        value="{{ \Carbon\Carbon::parse($paciente->fecha_nacimiento)->age }} años" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><strong>Cédula</strong></label>
                                    <input type="text" class="form-control" value="{{ $paciente->cedula }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label><strong>Tipo Sangre</strong></label>
                                    <input type="text" class="form-control" value="{{ $paciente->tipo_sangre  }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><strong>Dirección</strong></label>
                                    <input type="text" class="form-control" value="{{ $paciente->direccion ?? 'No especificada'}}" readonly>
                                </div>
                            </div>

                        </div>

                        <hr/>
                        <div class="form-group mb-2 mb20">
                            <strong>Alergias:</strong>
                               @if ($paciente->alergias->count())
                                    <ul>
                                        @foreach ($paciente->alergias as $alergia)
                                            <li>{{ $alergia->nombre }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-muted">No registra alergias</p>
                                @endif

                                <strong>Enfermedades:</strong>
                                @if ($paciente->enfermedades->count())
                                    <ul>
                                        @foreach ($paciente->enfermedades as $enfermedad)
                                            <li>{{ $enfermedad->nombre }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-muted">No registra enfermedades</p>
                                @endif
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

