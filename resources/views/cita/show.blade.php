@extends('adminlte::page')

@section('title')
    {{ config('adminlte.title') }}
    @hasSection('subtitle') | @yield('subtitle') @endif
@stop

@section('content')
<section class="content container-fluid pt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            {{-- TARJETA PRINCIPAL RESALTADA --}}
            <div class="card border-0 shadow-lg" style="border-radius: 15px;">
                
                {{-- ENCABEZADO CON ESTILO UNIFICADO --}}
                <div class="card-header bg-white border-bottom py-3 px-4" 
                     style="border-top: 5px solid #17a2b8; border-radius: 15px 15px 0 0;">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-2 mr-3 d-flex align-items-center justify-content-center" 
                             style="background-color: #e1f5fe; width: 45px; height: 45px;">
                            <i class="fas fa-info-circle text-info"></i>
                        </div>
                        <div>
                            <h3 class="card-title font-weight-bold text-dark mb-0" style="font-size: 1.2rem;">
                                Información Detallada de la Cita
                            </h3>
                        </div>
                        <div class="ml-auto">
                            <a class="btn btn-outline-primary btn-sm shadow-sm px-3" href="{{ route('cita.index') }}" style="border-radius: 8px;">
                                <i class="fas fa-arrow-left mr-1"></i> Regresar
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
                                    <img src="{{ asset('storage/'.$cita->paciente->usuario->foto) }}" alt="Foto" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <i class="fas fa-user fa-3x text-muted"></i>
                                @endif
                            </div>
                            <h5 class="font-weight-bold mb-0 text-dark">{{ $cita->paciente->usuario->nombre }}</h5>
                            <p class="text-muted">{{ $cita->paciente->usuario->apellido }}</p>
                        </div>

                        {{-- BLOQUE DERECHO: DETALLES --}}
                        <div class="col-md-8 pl-4">
                            <div class="row">
                                {{-- Médico y Especialidad --}}
                                <div class="col-md-6 mb-4">
                                    <label class="small font-weight-bold text-muted text-uppercase d-block">Médico Asignado</label>
                                    <div class="p-2 border-bottom bg-light rounded shadow-xs">
                                        <i class="fas fa-user-md mr-2 text-info"></i>
                                        <span class="text-dark font-weight-bold">{{ $cita->medico->usuario->nombre }} {{ $cita->medico->usuario->apellido }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="small font-weight-bold text-muted text-uppercase d-block">Especialidad</label>
                                    <div class="p-2 border-bottom bg-light rounded shadow-xs">
                                        <i class="fas fa-stethoscope mr-2 text-info"></i>
                                        <span class="text-dark font-weight-bold">{{ $cita->medico->especialidade->nombre }}</span>
                                    </div>
                                </div>

                                {{-- Fecha y Hora --}}
                                <div class="col-md-6 mb-4">
                                    <label class="small font-weight-bold text-muted text-uppercase d-block">Fecha Programada</label>
                                    <div class="p-2 border-bottom bg-light rounded shadow-xs">
                                        <i class="far fa-calendar-alt mr-2 text-primary"></i>
                                        <span class="text-dark font-weight-bold">{{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="small font-weight-bold text-muted text-uppercase d-block">Hora de Atención</label>
                                    <div class="p-2 border-bottom bg-light rounded shadow-xs">
                                        <i class="far fa-clock mr-2 text-primary"></i>
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

@section('footer')
    <div class="float-right text-muted">Version: {{ config('app.version', '1.0.0') }}</div>
    <strong>© 2025 - Consultorio El Buen Pastor. Desarrollado por Levi Ruiz y Erlin Silva.</strong>
@stop

@push('css')
<style>
    .shadow-xs { box-shadow: 0 .125rem .25rem rgba(0,0,0,.075)!important; }
    .border-left-info { border-left: 4px solid #17a2b8 !important; }
    .card-title { font-weight: 700; }
</style>
@endpush