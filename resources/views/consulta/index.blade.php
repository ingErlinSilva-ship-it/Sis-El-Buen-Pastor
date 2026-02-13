@extends('adminlte::page')

@section('title')
{{ config('adminlte.title') }}
@stop

{{-- 1. ENCABEZADO DINÁMICO UNIFICADO --}}
@section('content_header')
    <div class="container-fluid pt-2">
        <div class="row align-items-center">
            <div class="col-6 text-left">
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 1.6rem;">
                    <i class="fas fa-history text-primary mr-2"></i> {{ __('Historial de Consultas') }}
                </h1>
            </div>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            {{-- TARJETA PRINCIPAL RESALTADA --}}
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                
                {{-- BUSCADOR POR FECHAS INTEGRADO CON ESTILO ELEVADO --}}
                <div class="card-header bg-white border-bottom py-3 px-4" style="border-radius: 15px 15px 0 0;">
                    <form id="formFiltroConsultas" action="{{ route('consulta.index') }}" method="GET" class="row align-items-end filter-group">
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
                            {{-- BOTÓN FILTRAR CON INVERSIÓN TURQUESA --}}
                            <button type="submit" class="btn btn-outline-clinica shadow-sm font-weight-bold px-4">
                                <i class="fas fa-filter mr-1"></i> Filtrar Historial
                            </button>
                            {{-- BOTÓN REINICIAR CON INVERSIÓN NEGRO --}}
                            <a href="{{ route('consulta.index') }}" class="btn btn-invert-dark shadow-sm px-3 ml-1" title="Limpiar Filtros">
                                <i class="fas fa-undo"></i>
                            </a>
                        </div>
                    </form>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr class="text-muted">
                                    <th class="border-0 px-4 py-3" style="width: 50px;">No</th>
                                    <th class="border-0 py-3">Paciente</th>
                                    <th class="border-0 py-3 text-center">Médico</th>
                                    <th class="border-0 py-3">Diagnóstico</th>
                                    <th class="border-0 py-3 text-right px-4">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($consultas as $consulta)
                                    <tr class="fila-consulta">
                                        <td class="px-4 font-weight-bold text-muted align-middle">{{ ++$i }}</td>
                                        <td class="align-middle">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle mr-3 d-flex align-items-center justify-content-center bg-light border shadow-sm" 
                                                     style="width: 40px; height: 40px; overflow: hidden; flex-shrink: 0;">
                                                    @if($consulta->paciente->usuario?->foto)
                                                        <img src="{{ asset('storage/'.$consulta->paciente->usuario->foto) }}" alt="Foto" style="width: 100%; height: 100%; object-fit: cover;">
                                                    @else
                                                        <i class="fas fa-user text-muted"></i>
                                                    @endif
                                                </div>
                                                <div class="font-weight-bold text-dark" style="font-size: 0.95rem;">
                                                    {{ $consulta->paciente->usuario->nombre }} {{ $consulta->paciente->usuario->apellido }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center align-middle">
                                            <span class="text-muted small font-weight-bold">
                                                <i class="fas fa-stethoscope mr-1 text-info"></i> Dr. {{ $consulta->medico->usuario->nombre }}
                                            </span>
                                        </td>
                                        <td class="align-middle">
                                            <span class="text-dark font-italic" style="font-size: 0.9rem;">
                                                <i class="fas fa-comment-medical mr-1 text-primary small"></i>
                                                {{ Str::limit($consulta->diagnostico, 50) }}
                                            </span>
                                        </td>
                                        <td class="text-right align-middle px-4">
                                            <div class="btn-group">
                                                {{-- ACCIONES CON INVERSIÓN DE COLORES --}}
                                                <a class="btn btn-sm btn-invert-purple mx-1" href="{{ route('consulta.show', $consulta->id) }}" title="Ver">
                                                    <i class="fa fa-eye"></i>
                                                </a>

                                                @if(Auth::user()->rol_id != 3)
                                                    <a class="btn btn-sm btn-invert-success mx-1" href="{{ route('consulta.edit', $consulta->id) }}" title="Editar">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('consulta.destroy', $consulta->id) }}" method="POST" class="mb-0 form-eliminar d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-invert-danger mx-1" title="Eliminar">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            {{-- PAGINACIÓN --}}
            @if(method_exists($consultas, 'links'))
                <div class="mt-4 d-flex justify-content-center">
                    {!! $consultas->withQueryString()->links('pagination::bootstrap-4') !!}
                </div>
            @endif
        </div>
    </div>
</div>
@stop

@push('css')
<style>
    /* 1. ESTILOS DE INVERSIÓN DE BOTONES */
    .btn-outline-clinica, .btn-invert-dark, .btn-invert-purple, .btn-invert-success, .btn-invert-danger {
        background-color: #ffffff !important;
        border-radius: 10px !important;
        font-weight: 600 !important;
        transition: all 0.3s ease-in-out !important;
        border-width: 2px !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    /* Filtrar (Turquesa) */
    .btn-outline-clinica { border-color: #14b2c6 !important; color: #14b2c6 !important; }
    .btn-outline-clinica:hover { background-color: #14b2c6 !important; color: #ffffff !important; }

    /* Reiniciar (Negro) */
    .btn-invert-dark { border-color: #343a40 !important; color: #343a40 !important; }
    .btn-invert-dark:hover { background-color: #343a40 !important; color: #ffffff !important; }

    /* Ver (Púrpura) */
    .btn-invert-purple { border: 2px solid #6f42c1 !important; color: #6f42c1 !important; }
    .btn-invert-purple:hover { background-color: #6f42c1 !important; color: #ffffff !important; }

    /* Editar (Verde) */
    .btn-invert-success { border: 2px solid #28a745 !important; color: #28a745 !important; }
    .btn-invert-success:hover { background-color: #28a745 !important; color: #ffffff !important; }

    /* Eliminar (Rojo) */
    .btn-invert-danger { border: 2px solid #dc3545 !important; color: #dc3545 !important; }
    .btn-invert-danger:hover { background-color: #dc3545 !important; color: #ffffff !important; }

    /* 2. EFECTOS DE TABLA E INPUTS */
    .table-hover tbody tr:hover {
        background-color: #f1f7ff !important;
        transition: background-color 0.2s ease;
    }
    
    .filter-group input:focus {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0, 123, 255, 0.1) !important;
        border-color: #007bff !important;
    }

    .btn:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.1) !important; }
    .btn:hover i { color: #ffffff !important; }
    .shadow-xs { box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
</style>
@endpush

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Lógica de filtrado con colores unificados
    $('#formFiltroConsultas').on('submit', function(e) {
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

    // Confirmación eliminación
    $('.form-eliminar').submit(function (e) {
                    e.preventDefault();
                    Swal.fire({
                        title: '¿Eliminar Historial?',
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