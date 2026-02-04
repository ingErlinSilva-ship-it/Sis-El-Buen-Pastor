@extends('adminlte::page')

@section('title', 'Historial | ' . config('adminlte.title'))

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
                
                {{-- BUSCADOR POR FECHAS INTEGRADO (Igual que en Citas) --}}
                <div class="card-header bg-white border-bottom py-3 px-4" style="border-radius: 15px 15px 0 0;">
                    <form id="formFiltroConsultas" action="{{ route('consulta.index') }}" method="GET" class="row align-items-end">
                        <div class="col-md-3">
                            <label class="small font-weight-bold text-muted text-uppercase">Desde:</label>
                            <input type="date" name="fecha_inicio" class="form-control shadow-sm" 
                                   value="{{ request('fecha_inicio') }}" style="border-radius: 8px;">
                        </div>
                        <div class="col-md-3">
                            <label class="small font-weight-bold text-muted text-uppercase">Hasta:</label>
                            <input type="date" name="fecha_fin" class="form-control shadow-sm" 
                                   value="{{ request('fecha_fin') }}" style="border-radius: 8px;">
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="submit" class="btn btn-info shadow-sm px-4" style="border-radius: 8px; font-weight: bold;">
                                <i class="fas fa-filter mr-1"></i> Filtrar
                            </button>
                            <a href="{{ route('consulta.index') }}" class="btn btn-light border shadow-sm px-3" style="border-radius: 8px;" title="Limpiar Filtros">
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
                                    <tr>
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
                                                <i class="fas fa-user-md mr-1 text-info"></i> Dr. {{ $consulta->medico->usuario->nombre }}
                                            </span>
                                        </td>
                                        <td class="align-middle">
                                            <span class="text-dark font-italic" style="font-size: 0.9rem;">
                                                <i class="fas fa-comment-medical mr-1 text-primary small"></i>
                                                {{ Str::limit($consulta->diagnostico, 50) }}
                                            </span>
                                        </td>
                                        <td class="text-right align-middle px-4">
                                            <form action="{{ route('consulta.destroy', $consulta->id) }}" method="POST" class="mb-0 form-eliminar">
                                                <div class="btn-group">
                                                    <a class="btn btn-sm btn-light text-primary shadow-sm mr-1" href="{{ route('consulta.show', $consulta->id) }}" style="border-radius: 8px;" title="Ver Detalle">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    <a class="btn btn-sm btn-light text-success shadow-sm mr-1" href="{{ route('consulta.edit', $consulta->id) }}" style="border-radius: 8px;" title="Editar">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-light text-danger shadow-sm" style="border-radius: 8px;" title="Eliminar">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
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

@section('footer')
    <div class="float-right text-muted">Version: {{ config('app.version', '1.0.0') }}</div>
    <strong>© 2025 - Consultorio El Buen Pastor. Desarrollado por Levi Ruiz y Erlin Silva.</strong>
@stop

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // VALIDACIÓN DE FILTROS (Misma lógica que Citas)
    $('#formFiltroConsultas').on('submit', function(e) {
        let fechaInicio = $('input[name="fecha_inicio"]').val();
        let fechaFin = $('input[name="fecha_fin"]').val();

        if (fechaInicio === "" && fechaFin === "") {
            e.preventDefault();
            Swal.fire({
                icon: 'info',
                title: 'Rango requerido',
                text: 'Seleccione al menos una fecha para filtrar.',
                confirmButtonColor: '#17a2b8',
                borderRadius: '15px'
            });
            return false;
        }

        if (fechaInicio !== "" && fechaFin !== "" && fechaFin < fechaInicio) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Rango Inválido',
                text: 'La fecha "Hasta" no puede ser anterior a la "Desde".',
                confirmButtonColor: '#ffc107'
            });
            return false;
        }
    });

    // CONFIRMACIÓN DE ELIMINACIÓN
    $('.form-eliminar').submit(function(e) {
        e.preventDefault();
        Swal.fire({
            title: '¿Eliminar registro?',
            text: "Esta acción no se puede deshacer.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) { this.submit(); }
        });
    });

        // Notificación de éxito SweetAlert2
        @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: '¡Operación Exitosa!',
                    text: '{{ session("success") }}',
                    showConfirmButton: false,
                    timer: 2500,
                    timerProgressBar: true,
                    customClass: {
                        popup: 'rounded-lg'
                    }
                });
            @endif
    });
</script>
@endpush

@push('css')
<style>
    /* Efecto Hover Suave Unificado */
    .table-hover tbody tr:hover {
        background-color: #f1f7ff !important;
        transition: background-color 0.2s ease;
        cursor: pointer;
    }
    .table-hover tbody tr:hover td {
        box-shadow: inset 0 0 0 9999px rgba(0, 123, 255, 0.02);
    }
    .shadow-xs { box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    .badge { font-weight: 600; letter-spacing: 0.3px; }
</style>
@endpush