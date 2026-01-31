@extends('adminlte::page')

@section('title', 'Citas | ' . config('adminlte.title'))

@section('content_header')
    <div class="container-fluid pt-2">
        <div class="row align-items-center">
            <div class="col-6 text-left">
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 1.6rem;">
                    <i class="fas fa-calendar-check text-primary mr-2"></i> {{ __('Gestión de Citas') }}
                </h1>
            </div>
            <div class="col-6 text-right">
                <a href="{{ route('cita.create') }}" class="btn btn-primary shadow-sm px-4" style="border-radius: 50px; font-weight: bold;">
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
                {{-- Card principal --}}
                <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                    
                    {{-- Buscador por fechas --}}
                    <div class="card-header bg-white border-bottom py-3 px-4" style="border-radius: 15px 15px 0 0;">
                        <form id="formFiltroFechas" action="{{ route('cita.index') }}" method="GET" class="row align-items-end">
                            <div class="col-md-3">
                                <label class="small font-weight-bold text-muted text-uppercase">Desde:</label>
                                <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control shadow-sm" 
                                       value="{{ request('fecha_inicio') }}" style="border-radius: 8px;">
                            </div>
                            <div class="col-md-3">
                                <label class="small font-weight-bold text-muted text-uppercase">Hasta:</label>
                                <input type="date" name="fecha_fin" id="fecha_fin" class="form-control shadow-sm" 
                                       value="{{ request('fecha_fin') }}" style="border-radius: 8px;">
                            </div>
                            <div class="col-md-6 text-right">
                                <button type="submit" class="btn btn-info shadow-sm px-4" style="border-radius: 8px; font-weight: bold;">
                                    <i class="fas fa-filter mr-1"></i> Filtrar
                                </button>
                                <a href="{{ route('cita.index') }}" class="btn btn-light border shadow-sm px-3" style="border-radius: 8px;">
                                    <i class="fas fa-undo"></i>
                                </a>
                            </div>
                        </form>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light text-muted">
                                    <tr>
                                        <th class="border-0 px-4 py-3" style="width: 50px;">No</th>
                                        <th class="border-0 py-3">Paciente / Motivo</th>
                                        <th class="border-0 py-3 text-center">Médico</th>
                                        <th class="border-0 py-3 text-center">Fecha y Hora</th>
                                        <th class="border-0 py-3 text-center">Estado</th>
                                        <th class="border-0 py-3 text-right px-4">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($citas as $cita)
                                        <tr>
                                            <td class="px-4 font-weight-bold text-muted align-middle">{{ $loop->iteration }}</td>
                                            <td class="align-middle">
                                                <div class="d-flex align-items-center">
                                                    <div class="rounded-circle mr-3 d-flex align-items-center justify-content-center bg-light border shadow-sm" 
                                                         style="width: 45px; height: 45px; overflow: hidden; flex-shrink: 0;">
                                                        @if($cita->paciente->usuario?->foto)
                                                            <img src="{{ asset('storage/'.$cita->paciente->usuario->foto) }}" alt="Foto" style="width: 100%; height: 100%; object-fit: cover;">
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
                                                    <div class="btn-group">
                                                        <a class="btn btn-sm btn-light text-primary shadow-sm mr-1" href="{{ route('cita.show', $cita->id) }}" style="border-radius: 8px;"><i class="fa fa-eye"></i></a>
                                                        @can('doctor')
                                                            <a class="btn btn-sm btn-light text-success shadow-sm mr-1" href="{{ route('cita.edit', $cita->id) }}" style="border-radius: 8px;"><i class="fa fa-edit"></i></a>
                                                            <a class="btn btn-sm btn-light text-info shadow-sm mr-1" href="{{ route('consultas.atender', $cita->id) }}" style="border-radius: 8px;"><i class="fa fa-stethoscope"></i></a>
                                                        @endcan
                                                        @can('administrador')
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-light text-danger shadow-sm" style="border-radius: 8px;"><i class="fa fa-trash"></i></button>
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

@section('footer')
    <div class="float-right text-muted">Version: {{ config('app.version', '1.0.0') }}</div>
    <strong>© 2025 - Consultorio El Buen Pastor. Desarrollado por Levi Ruiz y Erlin Silva.</strong>
@stop

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Validación de filtros por fecha
    $('#formFiltroFechas').on('submit', function(e) {
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

    // Confirmación de eliminación
    $('.form-eliminar').submit(function(e) {
        e.preventDefault();
        Swal.fire({
            title: '¿Eliminar Cita?',
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
});
</script>
@endpush


@push('css')
<style>
    .table-hover tbody tr:hover {
        background-color: #f8fbff;
        transition: background-color 0.2s ease;
    }
    .shadow-xs {
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .badge {
        font-weight: 600;
        letter-spacing: 0.3px;
    }

    /* Efecto de resaltado al pasar el mouse por la fila */
    .table-hover tbody tr:hover {
        background-color: #f1f7ff !important; /* Azul muy suave */
        transition: background-color 0.2s ease;
        cursor: pointer;
    }

    /* Opcional: añade una sombra muy leve a la fila resaltada */
    .table-hover tbody tr:hover td {
        box-shadow: inset 0 0 0 9999px rgba(0, 123, 255, 0.02);
    }

    .shadow-xs {
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    .badge {
        font-weight: 600;
        letter-spacing: 0.3px;
    }
</style>
@endpush