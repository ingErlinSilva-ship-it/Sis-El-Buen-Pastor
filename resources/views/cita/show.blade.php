@extends('adminlte::page')

@section('title')
    {{ config('adminlte.title') }}
    @hasSection('subtitle') | @yield('subtitle') @endif
@stop

@section('content')

@php
    $temaColor = '#8e44ad'; 
    $temaFondoIcono = '#e2e8f0';
@endphp

<section class="content container-fluid pt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            {{-- TARJETA PRINCIPAL RESALTADA --}}
            <div class="card border-0 shadow-lg" style="border-radius: 15px;">
                
                {{-- ENCABEZADO CON ESTILO UNIFICADO Y DINÁMICO --}}
                    <div class="card-header bg-white border-bottom py-3 px-4" 
                        style="border-top: 5px solid {{ $temaColor }}; border-radius: 15px 15px 0 0;">
                        <div class="d-flex align-items-center">
                    
                            {{-- El fondo del icono ahora usa la variable de fondo --}}
                            <div class="rounded-circle p-2 mr-3 d-flex align-items-center justify-content-center" 
                                style="background-color: {{ $temaFondoIcono }}; width: 45px; height: 45px;">
                                <i class="fas fa-info-circle" style="color: {{ $temaColor }};"></i>
                            </div>
                            <div>
                                <h3 class="card-title font-weight-bold text-dark mb-0" style="font-size: 1.2rem;">
                                    Información Detallada de la Cita
                                </h3>
                            </div>
                
                            <div class="ml-auto d-flex align-items-center">

                                <a class="btn btn-invert btn-sm shadow-sm px-3 d-flex align-items-center mr-2" 
                                    href="{{ route('cita.index') }}" 
                                    style="border-radius: 10px; height: 38px;">
                                    <i class="fas fa-arrow-left mr-2"></i> Regresar
                                </a>

                                <a class="btn btn-purple-invert btn-sm shadow-sm px-3 d-flex align-items-center" 
                                    href="{{ route('cita.edit', $cita->id) }}" 
                                    style="border-radius: 10px; height: 38px;">
                                    <i class="fas fa-edit mr-2"></i> Editar Cita
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-4 bg-white">
                        <div class="row">
                            {{-- BLOQUE IZQUIERDO: PACIENTE --}}
                            <div class="col-md-4 text-center border-right">
                                <label class="text-muted font-weight-bold small text-uppercase mb-3 d-block">Paciente</label>
                                <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center bg-light border shadow-sm" 
                                    style="width: 120px; height: 120px; overflow: hidden;">
                                    @if($cita->paciente->usuario?->foto)
                                        <img src="{{ asset('storage/fotos/'.$cita->paciente->usuario->foto) }}" alt="Foto" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <i class="fas fa-user fa-3x text-muted"></i>
                                    @endif
                                </div>
                                <h5 class="font-weight-bold mb-0 text-dark">{{ $cita->paciente->usuario->nombre }} {{ $cita->paciente->usuario->apellido }}</h5>
                                <p class="text-muted">{{ $cita->paciente->usuario->email }}</p>
                            </div>

                            {{-- BLOQUE DERECHO: DETALLES --}}
                            <div class="col-md-8 pl-4">
                                <div class="row">
                                    {{-- Médico y Especialidad --}}
                                    <div class="col-md-6 mb-4">
                                        <label class="small font-weight-bold text-muted text-uppercase d-block">Médico Asignado</label>
                                        <div class="p-2 border-bottom bg-light rounded shadow-xs">
                                            <i class="fas fa-stethoscope mr-2 text-info" style="color: {{ $temaColor }} !important;"></i>
                                            <span class="text-dark font-weight-bold">{{ $cita->medico->usuario->nombre }} {{ $cita->medico->usuario->apellido }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-4">
                                        <label class="small font-weight-bold text-muted text-uppercase d-block">Especialidad</label>
                                        <div class="p-2 border-bottom bg-light rounded shadow-xs">
                                            <i class="fas fa-microscope mr-2 text-info" style="color: {{ $temaColor }} !important;"></i>
                                            <span class="text-dark font-weight-bold">{{ $cita->medico->especialidade->nombre }}</span>
                                        </div>
                                    </div>

                                    {{-- Fecha y Hora --}}
                                    <div class="col-md-6 mb-4">
                                        <label class="small font-weight-bold text-muted text-uppercase d-block">Fecha Programada</label>
                                        <div class="p-2 border-bottom bg-light rounded shadow-xs">
                                            <i class="far fa-calendar-alt mr-2 text-primary" style="color: {{ $temaColor }} !important;"></i>
                                            <span class="text-dark font-weight-bold">{{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }}</span>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-4">
                                        <label class="small font-weight-bold text-muted text-uppercase d-block">Hora de Atención</label>
                                        <div class="p-2 border-bottom bg-light rounded shadow-xs">
                                            <i class="far fa-clock mr-2 text-primary" style="color: {{ $temaColor }} !important;"></i>
                                            <span class="text-dark font-weight-bold">{{ \Carbon\Carbon::parse($cita->hora)->format('h:i A') }}</span>
                                        </div>
                                    </div>

                                    {{-- Estado --}}
                                    <div class="col-md-12 mb-4">
                                        <label class="small font-weight-bold text-muted text-uppercase d-block mb-2">Estado de la Cita</label>
                                        @php
                                            $statusClasses = [
                                                'pendiente' => 'bg-warning text-dark',
                                                'confirmada' => 'bg-success text-white',
                                                'cancelada' => 'bg-danger text-white',
                                                'asistida' => 'bg-info text-white'
                                            ];
                                            $label = ($cita->estado == 'asistida') ? 'Finalizada' : ucfirst($cita->estado);
                                        @endphp
                                        <div class="p-2 rounded font-weight-bold text-center {{ $statusClasses[$cita->estado] ?? 'bg-secondary' }} shadow-sm">
                                            {{ $label }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- MOTIVO --}}
                        <div class="mt-2">
                            <label class="small font-weight-bold text-muted text-uppercase d-block">Motivo de la Cita</label>
                            <div class="p-3 bg-light rounded border-left-info shadow-xs" style="min-height: 80px;">
                                <p class="mb-0 text-dark font-italic">"{{ $cita->motivo }}"</p>
                            </div>
                        </div>

                        {{-- METADATOS --}}
                        <div class="row mt-4 pt-3 border-top">
                            <div class="col-md-4">
                                <small class="text-muted"><i class="fas fa-hashtag mr-1"></i> <strong>ID Cita:</strong> #{{ $cita->id }}</small>
                            </div>
                            
                            <div class="col-md-4">
                                <small class="text-muted"><i class="fas fa-globe mr-1"></i> <strong>Origen:</strong> {{ ucfirst($cita->origen) }}</small>
                            </div>
                            
                            <div class="col-md-4">
                                <small class="text-muted"><i class="fas fa-stopwatch mr-1"></i> <strong>Duración:</strong> {{ $cita->duracion_minutos }} min</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop

@push('css')
<style>
    .shadow-xs { box-shadow: 0 .125rem .25rem rgba(0,0,0,.075)!important; }
    .border-left-info { border-left: 4px solid {{ $temaColor }} !important; }
    .card-title { font-weight: 700; }
</style>
@endpush

@push('css')
<style>
    /* 1. Inversión en NEGRO (Regresar) */
    .btn-invert {
        background-color: #ffffff !important;
        border: 2px solid #343a40 !important;
        color: #343a40 !important;
        font-weight: 600 !important;
        transition: all 0.3s ease-in-out !important;
    }
    .btn-invert:hover {
        background-color: #343a40 !important;
        color: #ffffff !important;
    }

    /* 2. Inversión en PÚRPURA (Editar - Usando el tema de la vista) */
    .btn-purple-invert {
        background-color: #ffffff !important;
        border: 2px solid {{ $temaColor }} !important;
        color: {{ $temaColor }} !important;
        font-weight: 600 !important;
        transition: all 0.3s ease-in-out !important;
    }
    .btn-purple-invert:hover {
        background-color: {{ $temaColor }} !important;
        color: #ffffff !important;
        box-shadow: 0 4px 10px rgba(111, 66, 193, 0.2) !important;
    }

    /* Efecto común para ambos al hacer hover */
    .btn:hover {
        transform: translateY(-2px);
    }
    .btn:hover i {
        color: #ffffff !important;
    }
</style>
@endpush