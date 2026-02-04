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

@extends('adminlte::page')

@section('title', 'Detalle de Consulta | ' . config('app.name'))

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-11">
            
            {{-- 1. BOTONES DE ACCIÓN (ESTILO GESTIÓN) --}}
            <div class="mb-4 d-flex justify-content-between align-items-center">
                <a href="{{ route('consulta.index') }}" class="btn btn-outline-secondary shadow-sm px-4" style="border-radius: 8px;">
                    <i class="fas fa-arrow-left mr-2"></i> Volver al Historial
                </a>
                <div>
                    {{-- Botón que abre el Modal de Receta --}}
                    <button type="button" class="btn btn-success shadow-sm px-4 mr-2" style="border-radius: 8px; font-weight: 600;" data-toggle="modal" data-target="#modalReceta">
                        <i class="fas fa-file-prescription mr-2"></i> Preparar Receta
                    </button>
                    {{-- Botón para PDF de la consulta completa --}}
                    <a href="{{ route('consulta.pdf_completo', $consulta->id) }}" class="btn btn-primary shadow-sm px-4" style="border-radius: 8px; font-weight: 600;">
                        <i class="fas fa-file-pdf mr-2"></i> Descargar Ficha Médica
                    </a>
                </div>
            </div>

            {{-- 2. VISTA SHOW (PANEL TÉCNICO) --}}
            <div class="card shadow-lg border-0" style="border-radius: 15px;">
                <div class="card-header bg-white py-3" style="border-top: 5px solid #17a2b8; border-radius: 15px 15px 0 0;">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h4 class="mb-0 font-weight-bold text-dark">Detalles de la Consulta</h4>
                        </div>
                        <div class="col-md-6 text-right">
                            <span class="badge badge-info p-2 shadow-xs">
                                <i class="fas fa-clock mr-1"></i> Inicio programado: {{ \Carbon\Carbon::parse($consulta->cita->hora)->format('h:i A') }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card-body bg-white">
                    <div class="row mb-4">
                        <div class="col-md-6 border-right">
                            <h6 class="text-primary font-weight-bold text-uppercase small"><i class="fas fa-user mr-2"></i>Paciente</h6>
                            <h5 class="mb-0">{{ $consulta->paciente->usuario->nombre }} {{ $consulta->paciente->usuario->apellido }}</h5>
                            <p class="text-muted small">Cédula: {{ $consulta->paciente->cedula ?? 'N/A' }} | Edad: {{ \Carbon\Carbon::parse($consulta->paciente->fecha_nacimiento)->age ?? 'N/A' }} años</p>
                        </div>
                        <div class="col-md-6 pl-md-4">
                            <h6 class="text-primary font-weight-bold text-uppercase small"><i class="fas fa-user-md mr-2"></i>Personal Médico</h6>
                            <h5 class="mb-0">Dr. {{ $consulta->medico->usuario->nombre }} {{ $consulta->medico->usuario->apellido }}</h5>
                            <p class="text-muted small">Especialidad: {{ $consulta->medico->especialidade->nombre }}</p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="bg-light p-2 font-weight-bold" style="border-radius: 5px;"><i class="fas fa-heartbeat mr-2 text-danger"></i>Signos Vitales Tomados</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered text-center mb-0 shadow-xs">
                                    <thead class="bg-light small font-weight-bold">
                                        <tr>
                                            <th>Peso</th><th>Estatura</th><th>P. Arterial</th><th>Temperatura</th><th>Frec. Cardíaca</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-dark">
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
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <h6 class="text-info font-weight-bold"><i class="fas fa-stethoscope mr-2"></i>Evaluación y Diagnóstico</h6>
                            <div class="p-3 border-left-info bg-light rounded shadow-xs">
                                <p class="mb-2"><strong>Síntomas:</strong> {{ $consulta->sintomas }}</p>
                                <p class="mb-0"><strong>Diagnóstico:</strong> <span class="text-dark">{{ $consulta->diagnostico }}</span></p>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <h6 class="text-success font-weight-bold"><i class="fas fa-file-prescription mr-2"></i>Prescripción Actual</h6>
                            <div class="p-3 border rounded shadow-xs" style="background-color: #f8fff9; border-left: 5px solid #28a745 !important;">
                                {!! nl2br(e($consulta->prescripcion)) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- 3. MODAL DE RECETA (PREVISUALIZACIÓN Y EDICIÓN) --}}
<div class="modal fade" id="modalReceta" tabindex="-1" role="dialog" aria-labelledby="modalRecetaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius: 15px;">
            <form action="{{ route('consulta.descargar_receta', $consulta->id) }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white" style="border-radius: 15px 15px 0 0;">
                    <h5 class="modal-title font-weight-bold" id="modalRecetaLabel">
                        <i class="fas fa-print mr-2"></i> Formato de Receta Médica
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body bg-light">
                    <div class="alert alert-info small py-2">
                        <i class="fas fa-info-circle mr-1"></i> Puede editar el texto de la receta aquí antes de descargar el PDF final.
                    </div>
                    
                    {{-- Simulador de Hoja Membretada --}}
                    <div class="p-5 bg-white shadow mx-auto" style="width: 100%; border: 1px solid #ddd; position: relative;">
                        <div class="text-center border-bottom pb-3 mb-4">
                            <h3 class="font-weight-bold text-uppercase mb-0" style="color: #1a4a72;">Consultorio EL BUEN PASTOR</h3>
                            <p class="small text-muted mb-0">Diriá, Granada | Tel: +505 8792-2112 </p>
                        </div>
                        <div class="row small mb-4">
                            <div class="col-6"><strong>Paciente:</strong> {{ $consulta->paciente->usuario->nombre }} {{ $consulta->paciente->usuario->apellido }}</div>
                            <div class="col-6 text-right"><strong>Fecha:</strong> {{ date('d/m/Y') }}</div>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold text-primary">INDICACIONES MÉDICAS:</label>
                            <textarea name="prescripcion_final" class="form-control" rows="12" 
                                      style="border: 2px dashed #cbd5e0; font-size: 1.1rem; line-height: 1.6;">{{ $consulta->prescripcion }}</textarea>
                        </div>
                        <div class="text-center mt-5 pt-4">
                            <div style="border-top: 1px solid #333; width: 200px; margin: 0 auto;"></div>
                            <small class="font-weight-bold">Firma y Sello Médico</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-white" style="border-radius: 0 0 15px 15px;">
                    <button type="button" class="btn btn-secondary shadow-sm" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success px-4 shadow-sm font-weight-bold">
                        <i class="fas fa-file-pdf mr-2"></i> Descargar Receta PDF
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@push('css')
<style>
    .border-left-info { border-left: 5px solid #17a2b8 !important; }
    .shadow-xs { box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    .rounded-xl { border-radius: 1rem; }
    
    /* Evitar que AdminLTE rompa el modal */
    .modal-content { border: none !important; }
</style>
@endpush

{{-- Create a common footer --}}

@section('footer')
    <div class="float-right text-muted">Version: {{ config('app.version', '1.0.0') }}</div>
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
<style>
    @media print {
        .main-sidebar, .main-header, .footer { display: none !important; }
        .content-wrapper { margin-left: 0 !important; }
        .card { border: none !important; box-shadow: none !important; }
    }
</style>
@endpush

