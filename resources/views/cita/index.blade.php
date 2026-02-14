@extends('adminlte::page')

@section('title')
{{ config('adminlte.title') }}
@stop

@section('content_header')
    <div class="container-fluid pt-2">
        <div class="row align-items-center">
            <div class="col-6 text-left">
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 1.6rem;">
                    <i class="fas fa-calendar-check text-primary mr-2"></i> {{ __('Gestión de Citas') }}
                </h1>
            </div>
            <div class="col-6 text-right">
                {{-- Botón Crear con inversión estilo Pacientes --}}
                <a href="{{ route('cita.create') }}" class="btn btn-invert-blue shadow-sm">
                    <i class="fas fa-plus mr-1"></i> {{ __('Crear Nueva Cita') }}
                </a>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                    
                    {{-- Filtro de Fechas con estilo de buscador elevado --}}
                    <div class="card-header bg-white border-bottom py-4 px-4" style="border-radius: 15px 15px 0 0;">
                        <form id="formFiltroFechas" action="{{ route('cita.index') }}" method="GET" class="row align-items-end filter-group">
                            <div class="col-md-3">
                                <label class="small font-weight-bold text-muted text-uppercase">Desde:</label>
                                <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control shadow-xs" 
                                       value="{{ request('fecha_inicio') }}" style="border-radius: 10px; border-width: 2px;">
                            </div>
                            <div class="col-md-3">
                                <label class="small font-weight-bold text-muted text-uppercase">Hasta:</label>
                                <input type="date" name="fecha_fin" id="fecha_fin" class="form-control shadow-xs" 
                                       value="{{ request('fecha_fin') }}" style="border-radius: 10px; border-width: 2px;">
                            </div>
                            <div class="col-md-6 text-right">
                                {{-- Botón Filtrar con inversión Turquesa/Info --}}
                                <button type="submit" class="btn btn-outline-clinica shadow-sm font-weight-bold px-4">
                                    <i class="fas fa-filter mr-1"></i> Filtrar Citas
                                </button>
                                {{-- Botón Reiniciar con inversión Negro --}}
                                <a href="{{ route('cita.index') }}" class="btn btn-invert-dark shadow-sm px-3 ml-1" title="Limpiar Filtros">
                                    <i class="fas fa-undo"></i>
                                </a>
                            </div>
                        </form>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0 px-4 py-3 text-muted" style="width: 50px;">No</th>
                                        <th class="border-0 py-3 text-muted">Paciente / Motivo</th>
                                        <th class="border-0 py-3 text-muted text-center">Médico</th>
                                        <th class="border-0 py-3 text-muted text-center">Fecha y Hora</th>
                                        <th class="border-0 py-3 text-muted text-center">Estado</th>
                                        <th class="border-0 py-3 text-right px-4 text-muted">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($citas as $cita)
                                        <tr class="fila-cita">
                                            <td class="px-4 font-weight-bold text-muted align-middle">{{ $loop->iteration }}</td>
                                            <td class="align-middle">
                                                <div class="d-flex align-items-center">
                                                    <div class="rounded-circle mr-3 d-flex align-items-center justify-content-center bg-light border shadow-sm" 
                                                         style="width: 45px; height: 45px; overflow: hidden; flex-shrink: 0;">
                                                        @if($cita->paciente->usuario?->foto)
                                                            <img src="{{ asset('storage/fotos/'.$cita->paciente->usuario->foto) }}" alt="Foto" style="width: 100%; height: 100%; object-fit: cover;">
                                                        @else
                                                            <i class="fas fa-user text-muted"></i>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <div class="font-weight-bold text-dark" style="font-size: 0.95rem;">
                                                            {{ $cita->paciente->usuario->nombre }} {{ $cita->paciente->usuario->apellido }}
                                                        </div>
                                                        <small class="text-primary d-block font-italic">
                                                            {{ Str::limit($cita->motivo, 35) }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center align-middle">
                                                <span class="text-muted small font-weight-bold">
                                                    Dr. {{ $cita->medico->usuario->nombre }}
                                                </span>
                                            </td>
                                            <td class="text-center align-middle">
                                                <div class="text-dark font-weight-bold">{{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }}</div>
                                                <small class="badge badge-light border text-muted px-2">{{ \Carbon\Carbon::parse($cita->hora)->format('h:i A') }}</small>
                                            </td>
                                            <td class="text-center align-middle">
                                                @php
                                                    $statusClasses = ['pendiente' => 'badge-warning text-dark', 'confirmada' => 'badge-success', 'cancelada' => 'badge-danger', 'asistida' => 'badge-info'];
                                                    $class = $statusClasses[$cita->estado] ?? 'badge-secondary';
                                                    $label = ($cita->estado == 'asistida') ? 'Finalizada' : ucfirst($cita->estado);
                                                @endphp
                                                <span class="badge {{ $class }} px-3 py-2 shadow-xs" style="border-radius: 50px;">
                                                    {{ $label }}
                                                </span>
                                            </td>
                                            <td class="text-right align-middle px-4">
                                                <form action="{{ route('cita.destroy', $cita->id) }}" method="POST" class="mb-0 form-eliminar">
                                                    <div class="d-flex justify-content-end">
                                                        {{-- Acciones con inversión de colores estilo Pacientes --}}
                                                        <a class="btn btn-sm btn-invert-purple mx-1" href="{{ route('cita.show', $cita->id) }}" title="Ver Detalle">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                        
                                                        @can('doctor')
                                                            <a class="btn btn-sm btn-invert-success mx-1" href="{{ route('cita.edit', $cita->id) }}" title="Editar">
                                                                <i class="fa fa-edit"></i>
                                                            </a>
                                                                
                                                            @if($cita->estado != 'asistida')
                                                                <a class="btn btn-sm btn-invert-warning mx-1" href="{{ route('consultas.atender', $cita->id) }}" title="Atender">
                                                                    <i class="fa fa-stethoscope"></i>
                                                                </a>
                                                            @endif
                                                        @endcan
                                                        
                                                        @can('administrador')
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-invert-danger mx-1" title="Eliminar">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        @endcan
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {{-- Paginación --}}
                @if(method_exists($citas, 'links'))
                    <div class="mt-4 d-flex justify-content-center">
                        {!! $citas->withQueryString()->links('pagination::bootstrap-4') !!}
                    </div>
                @endif
            </div>
        </div>
    </div>
@stop

@push('css')
<style>
    /* 1. ESTILOS DE INVERSIÓN (Basados en tu referencia de Pacientes) */
    .btn-invert-blue, .btn-invert-purple, .btn-invert-success, .btn-invert-danger, .btn-invert-warning, .btn-invert-dark, .btn-outline-clinica {
        background-color: #ffffff !important;
        border-radius: 10px !important;
        font-weight: 600 !important;
        transition: all 0.3s ease-in-out !important;
        border-width: 2px !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    /* Botón Crear (Azul) */
    .btn-invert-blue { border: 2px solid #007bff !important; color: #007bff !important; border-radius: 50px !important; padding: 6px 20px !important; }
    .btn-invert-blue:hover { background-color: #007bff !important; color: #ffffff !important; }

    /* Botón Filtro (Turquesa) */
    .btn-outline-clinica { border: 2px solid #14b2c6 !important; color: #14b2c6 !important; }
    .btn-outline-clinica:hover { background-color: #14b2c6 !important; color: #ffffff !important; }

    /* Botón Reiniciar (Negro) */
    .btn-invert-dark { border: 2px solid #343a40 !important; color: #343a40 !important; }
    .btn-invert-dark:hover { background-color: #343a40 !important; color: #ffffff !important; }

    /* Botones de Tabla */
    .btn-invert-purple { border: 2px solid #6f42c1 !important; color: #6f42c1 !important; }
    .btn-invert-purple:hover { background-color: #6f42c1 !important; color: #ffffff !important; }

    .btn-invert-success { border: 2px solid #28a745 !important; color: #28a745 !important; }
    .btn-invert-success:hover { background-color: #28a745 !important; color: #ffffff !important; }

    .btn-invert-warning { border: 2px solid #fd7e14 !important; color: #fd7e14 !important; }
    .btn-invert-warning:hover { background-color: #fd7e14 !important; color: #ffffff !important; }

    .btn-invert-danger { border: 2px solid #dc3545 !important; color: #dc3545 !important; }
    .btn-invert-danger:hover { background-color: #dc3545 !important; color: #ffffff !important; }

    /* 2. EFECTOS DE FILA Y ELEVACIÓN */
    .btn:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1) !important; }
    .btn:hover i { color: #ffffff !important; }

    .table-hover tbody tr:hover { background-color: #f1f7ff !important; transition: all 0.2s ease; }
    
    /* Estilo para los inputs de fecha al enfocar (Igual que buscador de Pacientes) */
    .filter-group input:focus {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0, 123, 255, 0.1) !important;
        border-color: #007bff !important;
    }

    .shadow-xs { box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05); }
    .badge { font-weight: 600; letter-spacing: 0.3px; }
</style>
@endpush

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Lógica de filtros
    $('#formFiltroFechas').on('submit', function(e) {
        let fechaInicio = $('#fecha_inicio').val();
        let fechaFin = $('#fecha_fin').val();
        if (fechaInicio === "" && fechaFin === "") {
            e.preventDefault();
            Swal.fire({
                icon: 'info',
                title: 'Rango requerido',
                text: 'Seleccione al menos una fecha para filtrar.',
                confirmButtonColor: '#14b2c6',
                borderRadius: '15px'
            });
            return false;
        }
    });

    // Confirmación eliminación (estilo Pacientes)
    $('.form-eliminar').submit(function (e) {
                    e.preventDefault();
                    Swal.fire({
                        title: '¿Eliminar Cita?',
                        text: "Esta acción es irreversible y afectará el historial clínico.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: '<i class="fas fa-trash"></i> Sí, eliminar',
                        cancelButtonText: 'Cancelar',
                        reverseButtons: true,
                        customClass: {
                            confirmButton: 'btn btn-danger px-4 mx-2',
                            cancelButton: 'btn btn-secondary px-4 mx-2',
                            popup: 'rounded-lg'
                        },
                        buttonsStyling: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.submit();
                        }
                    });
                });     

                @if(session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: '¡Operación Exitosa!',
                        text: '{{ session("success") }}',
                        showConfirmButton: false,
                        timer: 2500,
                        timerProgressBar: true
                    });
                @endif

                $(function () {
                    $('[data-toggle="tooltip"]').tooltip()
                })
});
</script>
@endpush