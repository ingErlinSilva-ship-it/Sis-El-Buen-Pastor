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
            <div class="card shadow-sm">
                {{-- CABECERA --}}
                <div class="card-header bg-white" style="display: flex; justify-content: space-between; align-items: center;">
                    <div class="float-left">
                        <span class="card-title font-weight-bold"></i>Información del Paciente</span>
                    </div>
                    <div class="ml-auto">
                        <a class="btn btn-primary btn-sm" href="{{ route('paciente.index') }}">
                            <i class="fas fa-arrow-left"></i> Regresar
                        </a>
                    </div>
                </div>

                {{-- CUERPO --}}
                <div class="card-body bg-white">
                    {{-- DATOS GENERALES --}}
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
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><strong>Fecha de Nacimiento</strong></label>
                                <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($paciente->fecha_nacimiento)->format('d/m/Y') }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><strong>Edad Actual</strong></label>
                                <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($paciente->fecha_nacimiento)->age }} años" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><strong>Cédula</strong></label>
                                <input type="text" class="form-control" value="{{ $paciente->cedula }}" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><strong>Tipo Sangre</strong></label>
                                <input type="text" class="form-control text-danger font-weight-bold" value="{{ $paciente->tipo_sangre }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="form-group">
                                <label><strong>Dirección</strong></label>
                                <input type="text" class="form-control" value="{{ $paciente->direccion ?? 'No especificada' }}" readonly>
                            </div>
                        </div>
                    </div>

                    {{-- SECCIÓN DEL TUTOR: SOLO SE MUESTRA SI ES MENOR --}}
                    @if($paciente->es_menor)
                        <hr class="my-4">
                        <h5 class="text-primary font-weight-bold mb-3"><i class="fas fa-user-shield mr-2"></i>Información del Responsable (Tutor)</h5>
                        <div class="row p-3 rounded" style="background-color: #f1f5f9; border: 1px solid #e2e8f0;">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><strong>Nombre Completo</strong></label>
                                    <input type="text" class="form-control" value="{{ $paciente->tutor_nombre }} {{ $paciente->tutor_apellido }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label><strong>Parentesco</strong></label>
                                    <input type="text" class="form-control" value="{{ $paciente->tutor_parentesco }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label><strong>Cédula</strong></label>
                                    <input type="text" class="form-control @error('tutor_cedula') is-invalid @enderror" value="{{ $paciente->tutor_cedula ?? 'N/A' }}" readonly>
                                    @error('tutor_cedula')
                                        <span class="invalid-feedback" style="display: block;">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label><strong>Teléfono</strong></label>
                                    <input type="text" class="form-control" value="{{ $paciente->tutor_telefono }}" readonly>
                                </div>
                            </div>
                        </div>
                    @endif

                    <hr class="my-4">

                    {{-- SECCIÓN DE ALERGIAS Y ENFERMEDADES --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-outline card-warning shadow-none border">
                                <div class="card-header">
                                    <h3 class="card-title text-warning font-weight-bold"><i class="fas fa-allergies mr-2"></i>Alergias</h3>
                                </div>
                                <div class="card-body">
                                    @if ($paciente->alergias->count())
                                        <ul class="mb-0">
                                            @foreach ($paciente->alergias as $alergia)
                                                <li>{{ $alergia->nombre }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-muted mb-0">No registra alergias.</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card card-outline card-danger shadow-none border">
                                <div class="card-header">
                                    <h3 class="card-title text-danger font-weight-bold"><i class="fas fa-file-medical mr-2"></i>Enfermedades</h3>
                                </div>
                                <div class="card-body">
                                    @if ($paciente->enfermedades->count())
                                        <ul class="mb-0">
                                            @foreach ($paciente->enfermedades as $enfermedade)
                                                <li>{{ $enfermedade->nombre }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-muted mb-0">No registra enfermedades de base.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div> {{-- Cierra row de alergias/enfermedades --}}

                </div> {{-- Cierra card-body principal --}}
            </div> {{-- Cierra card principal --}}
        </div> {{-- Cierra col-md-12 --}}
    </div> {{-- Cierra row principal --}}
</section>
@stop

{{-- Create a common footer --}}

@section('footer')
    <div class="float-right">Version: {{ config('app.version', '1.0.0') }}</div>
    <strong>© 2025 - Consultorio El Buen Pastor. Desarrollado por Levi Ruiz y Erlin Silva.</strong>
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

