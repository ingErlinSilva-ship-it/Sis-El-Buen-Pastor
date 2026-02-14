@extends('adminlte::page')

@section('title')
{{ config('adminlte.title') }}
@stop

@section('content')

@php
    $temaColor = '#8e44ad'; 
    $temaFondoIcono = '#f3e5f5';
@endphp

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-11">
            
            {{-- 1. BOTONES DE ACCIÓN (ESTILO INVERSIÓN) --}}
            <div class="mb-4 d-flex justify-content-between align-items-center">
                {{-- Inversión Negro --}}
                <a href="{{ route('consulta.index') }}" class="btn btn-invert shadow-sm px-4">
                    <i class="fas fa-arrow-left mr-2"></i> Volver al Historial
                </a>
                
                <div class="d-flex">
                    {{-- Inversión Azul (Descarga Directa PDF) --}}
                    <a href="{{ route('consulta.pdf_receta', $consulta->id) }}" class="btn btn-primary-invert shadow-sm px-4">
                        <i class="fas fa-file-pdf mr-2"></i> Descargar Ficha 
                    </a>
                </div>
            </div>

            {{-- 2. VISTA SHOW (PANEL TÉCNICO) --}}
            <div class="card shadow-lg border-0" style="border-radius: 15px;">
                <div class="card-header bg-white py-3" style="border-top: 5px solid {{ $temaColor }}; border-radius: 15px 15px 0 0;">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h4 class="mb-0 font-weight-bold text-dark">Detalles de la Consulta</h4>
                        </div>
                        <div class="col-md-6 text-right">
                            <span class="badge p-2 shadow-xs" style="border-radius: 8px; color: {{ $temaColor }}; background-color: {{ $temaFondoIcono }};">
                                <i class="fas fa-clock mr-1"></i> Inicio programado: {{ \Carbon\Carbon::parse($consulta->cita->hora)->format('h:i A') }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card-body bg-white">
                    <div class="row mb-4">
                        <div class="col-md-6 border-right">
                            <h6 class="font-weight-bold text-uppercase small" style="color: {{ $temaColor }};"><i class="fas fa-user mr-2"></i>Paciente</h6>
                            <h5 class="mb-0 font-weight-bold">{{ $consulta->paciente->usuario->nombre }} {{ $consulta->paciente->usuario->apellido }}</h5>
                            <p class="text-muted small">Cédula: {{ $consulta->paciente->cedula ?? 'N/A' }} | Edad: {{ \Carbon\Carbon::parse($consulta->paciente->fecha_nacimiento)->age ?? 'N/A' }} años</p>
                        </div>
                        <div class="col-md-6 pl-md-4">
                            <h6 class="font-weight-bold text-uppercase small" style="color: {{ $temaColor }};"><i class="fas fa-stethoscope mr-2"></i>Personal Médico</h6>
                            <h5 class="mb-0 font-weight-bold">Dr. {{ $consulta->medico->usuario->nombre }} {{ $consulta->medico->usuario->apellido }}</h5>
                            <p class="text-muted small">Especialidad: {{ $consulta->medico->especialidade->nombre }}</p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="bg-light p-2 font-weight-bold" style="border-radius: 8px;"><i class="fas fa-heartbeat mr-2 text-danger"></i>Signos Vitales Tomados</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered text-center mb-0 shadow-xs" style="border-radius: 8px; overflow: hidden;">
                                    <thead class="bg-light small font-weight-bold">
                                        <tr>
                                            <th>Peso</th><th>Estatura</th><th>P. Arterial</th><th>Temperatura</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-dark font-weight-bold">
                                        <tr>
                                            <td>{{ $consulta->peso ?? '---' }} kg</td>
                                            <td>{{ $consulta->estatura ?? '---' }} m</td>
                                            <td>{{ $consulta->presion_arterial ?? '---' }}</td>
                                            <td>{{ $consulta->temperatura ?? '---' }} °C</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <h6 class="font-weight-bold" style="color: {{ $temaColor }};"><i class="fas fa-search-plus mr-2"></i>Evaluación y Diagnóstico</h6>
                            <div class="p-3 border rounded shadow-xs" style="background-color: #fcfaff; border-left: 5px solid {{ $temaColor }} !important;">
                                <p class="mb-2"><strong>Síntomas:</strong> {{ $consulta->sintomas }}</p>
                                <p class="mb-0"><strong>Diagnóstico:</strong> <span class="text-dark font-weight">{{ $consulta->diagnostico }}</span></p>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <h6 class="font-weight-bold" style="color: {{ $temaColor }};"><i class="fas fa-file-prescription mr-2"></i>Prescripción Actual</h6>
                            <div class="p-3 border rounded shadow-xs" style="background-color: #fcfaff; border-left: 5px solid {{ $temaColor }} !important;">
                                {!! nl2br(e($consulta->prescripcion)) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@push('css')
<style>
    /* SISTEMA DE INVERSIÓN UNIFICADO */
    .btn-invert, .btn-success-invert, .btn-primary-invert {
        background-color: #fff !important;
        border-radius: 10px !important;
        font-weight: 600 !important;
        transition: all 0.3s ease !important;
        border-width: 2px !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    /* Volver (Negro) */
    .btn-invert { border: 2px solid #343a40 !important; color: #343a40 !important; }
    .btn-invert:hover { background-color: #343a40 !important; color: #fff !important; }

    /* Editar (Verde) */
    .btn-success-invert { border: 2px solid #28a745 !important; color: #28a745 !important; }
    .btn-success-invert:hover { background-color: #28a745 !important; color: #fff !important; }

    /* Descargar (Azul) */
    .btn-primary-invert { border: 2px solid #007bff !important; color: #007bff !important; }
    .btn-primary-invert:hover { background-color: #007bff !important; color: #fff !important; }

    /* Efectos generales */
    .btn:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.1) !important; }
    .btn:hover i { color: #fff !important; }

    .shadow-xs { box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    
    /* Pequeño ajuste visual para los bloques de texto */
    .bg-fcfaff { background-color: #fcfaff !important; }
</style>
@endpush