@extends('adminlte::page')

@section('title', 'Expediente | ' . $paciente->usuario->nombre)

@section('content')
<div class="container-fluid pt-4">
    {{-- BOTÓN REGRESAR POR ENCIMA DE LA CARD --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="text-dark font-weight-bold">Expediente Clínico Digital</h3>
        <a class="btn btn-primary shadow-sm" href="{{ route('paciente.index') }}" style="border-radius: 50px;">
            <i class="fas fa-arrow-left mr-1"></i> Regresar a la lista
        </a>
    </div>

    <div class="row">
        {{-- COLUMNA IZQUIERDA: FICHA DEL PACIENTE --}}
        <div class="col-md-4">
            <div class="card card-primary card-outline shadow-sm" style="border-radius: 15px;">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center bg-light mb-3 shadow-sm" 
                             style="width: 100px; height: 100px; overflow: hidden; border: 3px solid #3c8dbc;">
                            @if($paciente->usuario->foto)
                                <img src="{{ asset('storage/'.$paciente->usuario->foto) }}" alt="Foto" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <i class="fas fa-user-injured fa-3x text-muted"></i>
                            @endif
                        </div>
                    </div>

                    <h3 class="profile-username text-center font-weight-bold text-capitalize">
                        {{ $paciente->usuario->nombre }} {{ $paciente->usuario->apellido }}
                    </h3>
                    <p class="text-muted text-center mb-1">
                        {{ $paciente->fecha_nacimiento ? \Carbon\Carbon::parse($paciente->fecha_nacimiento)->age . ' años' : 'N/A' }}
                    </p>
                    
                    <div class="text-center mb-3">
                        <span class="badge badge-pill badge-danger px-3 shadow-sm">
                            <i class="fas fa-tint mr-1"></i> Sangre: {{ $paciente->tipo_sangre }}
                        </span>
                    </div>

                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item border-0 px-0">
                            <b>Cédula:</b> <span class="float-right text-muted">{{ $paciente->cedula ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item border-0 px-0">
                            <b>Fecha Nac.:</b> 
                            <span class="float-right text-muted">
                                @if($paciente->fecha_nacimiento)
                                    {{ \Carbon\Carbon::parse($paciente->fecha_nacimiento)->format('d/m/Y') }}
                                @else
                                    <span class="badge badge-warning">Pendiente de completar</span>
                                @endif
                            </span>
                        </li>
                        <li class="list-group-item border-0 px-0">
                            <b>Contacto:</b> <span class="float-right text-muted">{{ $paciente->usuario->celular ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item border-0 px-0">
                            <b class="d-block mb-1">Dirección:</b>
                            <span class="text-muted small">{{ $paciente->direccion ?? 'No especificada' }}</span>
                        </li>
                    </ul>

                    {{-- SECCIÓN DINÁMICA DEL RESPONSABLE --}}
                    @if($paciente->es_menor)
                        <div class="p-3 mb-3" style="background-color: #f0f7ff; border-radius: 12px; border: 1px solid #d1e9ff;">
                            <h6 class="font-weight-bold text-primary mb-2" style="font-size: 0.8rem; letter-spacing: 0.5px;">
                                <i class="fas fa-user-shield mr-1"></i> DATOS DEL RESPONSABLE
                            </h6>
                            <p class="mb-1 small"><b>Nombre:</b> {{ $paciente->tutor_nombre }} {{ $paciente->tutor_apellido }}</p>
                            <p class="mb-1 small"><b>Parentesco:</b> {{ $paciente->tutor_parentesco }}</p>
                            <p class="mb-1 small"><b>Cédula:</b> {{ $paciente->tutor_cedula ?? 'N/A' }}</p>
                            <p class="mb-0 small"><b>Teléfono:</b> {{ $paciente->tutor_telefono }}</p>
                        </div>
                    @endif

                    {{-- BOTÓN RESUMEN IA (MAQUETA) --}}
                    <div class="bg-light p-3 rounded mb-2 border border-primary shadow-sm" style="border-style: dashed !important;">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="font-weight-bold text-primary mb-0"><i class="fas fa-robot mr-1"></i> Resumen IA</h6>
                            <button id="btnGenerarIA" class="btn btn-primary btn-xs font-weight-bold shadow-sm" style="border-radius: 5px;">Generar</button>
                        </div>
                        <div id="resumenIAContenido">
                            <p class="small text-muted mb-0 italic">"Haz clic en generar para obtener un resumen inteligente."</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ALERGIAS Y ENFERMEDADES (MISMO ESTILO QUE DISEÑAMOS) --}}
            <div class="card shadow-sm" style="border-radius: 15px;">
                <div class="card-body p-3">
                    <h6 class="font-weight-bold text-danger mb-2"><i class="fas fa-allergies mr-1"></i> Alergias</h6>
                    @forelse($paciente->alergias as $alergia)
                        <span class="badge badge-light border mb-1 shadow-xs">{{ $alergia->nombre }}</span>
                    @empty
                        <p class="text-muted small mb-0">No registra alergias.</p>
                    @endforelse

                    <hr class="my-3">

                    <h6 class="font-weight-bold text-warning mb-2"><i class="fas fa-file-medical mr-1"></i> Enfermedades</h6>
                    @forelse($paciente->enfermedades as $enfermedad)
                        <span class="badge badge-light border mb-1 shadow-xs">{{ $enfermedad->nombre }}</span>
                    @empty
                        <p class="text-muted small mb-0">No registra enfermedades.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- COLUMNA DERECHA: LÍNEA DE TIEMPO DINÁMICA --}}
        <div class="col-md-8">
            <div class="card shadow-sm" style="border-radius: 15px;">
                <div class="card-header bg-white border-0">
                    <h3 class="card-title font-weight-bold text-dark"><i class="fas fa-history mr-2 text-primary"></i>Historial de Atenciones Médicas</h3>
                </div>
                <div class="card-body">
                    {{-- SI TIENE CONSULTAS --}}
                    @if(isset($paciente->consultas) && $paciente->consultas->count() > 0)
                        <div class="timeline timeline-inverse">
                            @foreach($paciente->consultas as $consulta)
                                <div class="time-label">
                                    <span class="bg-primary px-3 shadow-sm" style="border-radius: 5px;">
                                        {{ \Carbon\Carbon::parse($consulta->cita->fecha)->format('d/m/Y') }}
                                    </span>
                                </div>
                                <div>
                                    <i class="fas fa-stethoscope bg-info shadow-sm"></i>
                                    <div class="timeline-item shadow-sm border" style="border-radius: 10px;">
                                        <span class="time text-muted"><i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($consulta->cita->hora)->format('h:i A') }}</span>
                                        <h3 class="timeline-header font-weight-bold text-primary">Diagnóstico: {{ $consulta->diagnostico }}</h3>
                                        <div class="timeline-body">
                                            <p class="mb-1 text-dark"><b>Tratamiento:</b> {{ $consulta->prescripcion }}</p>
                                            @if($consulta->observaciones)
                                                <p class="text-muted small mb-0"><i>"{{ $consulta->observaciones }}"</i></p>
                                            @endif
                                        </div>
                                        <div class="timeline-footer p-2 bg-light rounded-bottom text-right">
                                            <small class="text-muted">Atendido por: 
                                            <b class="text-uppercase">
                                                Dr. {{ $consulta->medico->usuario->nombre }} {{ $consulta->medico->usuario->apellido }}
                                            </b>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <div><i class="far fa-clock bg-gray"></i></div>
                        </div>
                    @else
                        {{-- SI NO TIENE CONSULTAS --}}
                        <div class="text-center py-5">
                            <i class="fas fa-folder-open fa-4x text-light mb-3"></i>
                            <h5 class="text-muted font-weight-bold">Historial Vacío</h5>
                            <p class="text-muted small">Este paciente aún no registra consultas médicas en el sistema.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@push('js')
<script>
    $('#btnGenerarIA').click(function() {
        const btn = $(this);
        const contenedor = $('#resumenIAContenido');
        
        btn.html('<i class="fas fa-spinner fa-spin"></i> Generando...').prop('disabled', true);
        contenedor.html('<p class="text-muted italic">Analizando historial clínico...</p>');

        $.ajax({
            url: "{{ route('paciente.resumen.ia', $paciente->id) }}",
            method: 'GET',
            success: function(response) {
                // Formateamos el texto (puedes usar una librería de Markdown si quieres)
                contenedor.html('<div class="small">' + response.resumen.replace(/\n/g, '<br>') + '</div>');
                btn.html('Regenerar').prop('disabled', false);
            },
            error: function() {
                contenedor.html('<p class="text-danger small">Error al conectar con Gemini. Intente más tarde.</p>');
                btn.html('Generar').prop('disabled', false);
            }
        });
    });
</script>
@endpush
@push('css')
<style>
    .timeline::before { border-radius: 0.25rem; background-color: #dee2e6; bottom: 0; content: ""; left: 31px; margin: 0; position: absolute; top: 0; width: 4px; }
    .timeline > div > i { width: 30px; height: 30px; font-size: 15px; line-height: 30px; position: absolute; color: #fff; border-radius: 50%; text-align: center; left: 18px; top: 0; }
    .timeline-item { margin-left: 60px; margin-right: 15px; margin-top: 0; position: relative; }
</style>
@endpush
