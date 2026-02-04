@extends('adminlte::page')

@section('title', 'Pacientes | ' . config('adminlte.title'))

@section('content_header')
    <div class="container-fluid pt-2">
        {{-- BARRA DE BÚSQUEDA Y FILTROS --}}
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="input-group shadow-sm" style="border-radius: 10px; overflow: hidden;">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-white border-right-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                    </div>
                    <input type="text" id="search_nombre" class="form-control border-left-0" placeholder="Escriba el nombre del paciente para buscar...">
                </div>
            </div>
            <div class="col-md-4 text-right">
                <button id="btn_filtro_incompleto" class="btn btn-outline-clinica shadow-sm font-weight-bold" style="border-radius: 10px; min-width: 220px;">
                    <i class="fas fa-file-medical-alt mr-1"></i> Expedientes Incompletos
                </button>
            </div>
        </div>

        <div class="row align-items-center">
            <div class="col-6 text-left">
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 1.6rem;">
                    <i class="fas fa-user-injured text-primary mr-2"></i> {{ __('Pacientes') }}
                </h1>
            </div>
            <div class="col-6 text-right">
                <a href="{{ route('paciente.create') }}" class="btn btn-primary shadow-sm px-3" style="border-radius: 50px; font-weight: bold;">
                    <i class="fas fa-plus mr-1"></i> {{ __('Crear Nuevo Paciente') }}
                </a>
            </div>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm" style="border-radius: 15px;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 px-4 py-3 text-muted" style="width: 50px;">No</th>
                            <th class="border-0 py-3 text-muted">Paciente</th>
                            <th class="border-0 py-3 text-muted text-center">Datos Clínicos</th>
                            <th class="border-0 py-3 text-muted text-center">Cédula</th>
                            <th class="border-0 py-3 text-muted text-center">Dirección</th>
                            <th class="border-0 py-3 text-right px-4 text-muted">{{ __('Acciones') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pacientes as $paciente)
                            @php
                                $esIncompleto = !$paciente->fecha_nacimiento || !$paciente->tipo_sangre || 
                                               !$paciente->direccion || (!$paciente->es_menor && !$paciente->cedula);
                            @endphp
                            {{-- FILA ÚNICA CORREGIDA --}}
                            <tr class="fila-paciente {{ $esIncompleto ? 'incompleto' : '' }}">
                                <td class="align-middle px-4 text-muted">{{ ++$i }}</td>
                                <td class="align-middle">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle mr-3 d-flex align-items-center justify-content-center bg-light border shadow-sm" style="width: 45px; height: 45px; overflow: hidden;">
                                            @if($paciente->usuario?->foto)
                                                <img src="{{ asset('storage/'.$paciente->usuario->foto) }}" alt="Foto" style="width: 100%; height: 100%; object-fit: cover;">
                                            @else
                                                <i class="fas fa-user text-muted"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <span class="font-weight-bold text-dark d-block text-capitalize nombre-paciente">
                                                {{ $paciente->usuario?->nombre }} {{ $paciente->usuario?->apellido }}
                                            </span>
                                            <small class="text-muted">
                                                {{ $paciente->fecha_nacimiento ? \Carbon\Carbon::parse($paciente->fecha_nacimiento)->age . ' años' : 'Edad N/A' }} | {{ $paciente->usuario?->email }}
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center align-middle">
                                    <span class="badge badge-pill shadow-sm mb-1" 
                                          style="background-color: #ffebee; color: #c62828; padding: 0.5em 1em; border: 1px solid #ffcdd2;"
                                          data-toggle="tooltip" 
                                          title="{{ $paciente->tipo_sangre ? 'Tipo de sangre verificado' : 'Completar el tipo de sangre' }}">
                                        <i class="fas fa-tint mr-1"></i> {{ $paciente->tipo_sangre ?? 'S/D' }}
                                    </span>
                                    <br>
                                    <small class="{{ $paciente->fecha_nacimiento ? 'text-muted' : 'text-warning font-weight-bold' }}">
                                        <i class="fas fa-calendar-alt mr-1"></i> 
                                        {{ $paciente->fecha_nacimiento ? \Carbon\Carbon::parse($paciente->fecha_nacimiento)->format('d/m/Y') : 'Fecha pendiente' }}
                                    </small>
                                </td>
                                <td class="text-center align-middle">
                                    @if(!$paciente->es_menor && !$paciente->cedula)
                                        <span class="text-danger small font-weight-bold" title="Identificación necesaria para trámites legales" data-toggle="tooltip">
                                            <i class="fas fa-id-card"></i> Falta Cédula
                                        </span>
                                    @else
                                        <span class="text-muted small font-weight-bold">
                                            {{ $paciente->cedula ?? 'Menor (S/C)' }}
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center align-middle text-muted small" style="max-width: 200px;" 
                                    data-toggle="tooltip" 
                                    title="{{ $paciente->direccion ? 'Dirección registrada' : 'Completa la Dirección' }}">
                                    <i class="fas fa-map-marker-alt mr-1 {{ $paciente->direccion ? 'text-primary' : 'text-warning' }}"></i>
                                    {{ Str::limit($paciente->direccion ?? 'Sin dirección', 35) }}
                                </td>
                                <td class="text-right align-middle px-4">
                                    <form action="{{ route('paciente.destroy', $paciente->id) }}" method="POST" class="mb-0 form-eliminar">
                                        <div class="btn-group">
                                            <a class="btn btn-sm btn-light text-primary shadow-sm mr-1" href="{{ route('paciente.show', $paciente->id) }}" title="Ver Expediente">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @can('doctor')
                                                <a class="btn btn-sm btn-light text-success shadow-sm mr-1" href="{{ route('paciente.edit', $paciente->id) }}" title="Editar">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                            @endcan
                                            @csrf @method('DELETE')
                                            @can('administrador')
                                                <button type="submit" class="btn btn-sm btn-light text-danger shadow-sm" title="Eliminar">
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
    <div class="mt-4 d-flex justify-content-center">
        {!! $pacientes->withQueryString()->links('pagination::bootstrap-4') !!}
    </div>
</div>
@stop

{{-- ... tus secciones de Footer, JS (corregido abajo) y CSS se mantienen ... --}}

@section('footer')
    <div class="float-right">Version: {{ config('app.version', '1.0.0') }}</div>
    <strong>© 2025 - Consultorio El Buen Pastor. Desarrollado por Levi Ruiz y Erlin Silva.</strong>
@stop

{{-- Usamos el mismo JS de Usuarios para las alertas bonitas --}}
@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>    
    $(document).ready(function() {
        // Activar Tooltips
        $('[data-toggle="tooltip"]').tooltip();

        // 1. BUSCADOR POR NOMBRE EN TIEMPO REAL
        $("#search_nombre").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $(".fila-paciente").filter(function() {
                $(this).toggle($(this).find('.nombre-paciente').text().toLowerCase().indexOf(value) > -1)
            });
        });

        // 2. FILTRO DE EXPEDIENTES INCOMPLETOS
        let filtrandoIncompletos = false;
        $("#btn_filtro_incompleto").on("click", function() {
            filtrandoIncompletos = !filtrandoIncompletos;
            
            if(filtrandoIncompletos) {
                $(this).addClass('active btn-info text-white').removeClass('btn-outline-info');
                $(this).html('<i class="fas fa-users"></i> Ver Todos');
                $(".fila-paciente").hide();
                $(".fila-paciente.incompleto").fadeIn();
            } else {
                $(this).removeClass('active btn-info text-white').addClass('btn-outline-info');
                $(this).html('<i class="fas fa-filter"></i> Expedientes Incompletos');
                $(".fila-paciente").fadeIn();
            }
        });

        $('.form-eliminar').submit(function(e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Eliminar paciente?',
                text: "Esta acción es irreversible y afectará el historial clínico.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                customClass: {
                    confirmButton: 'btn btn-danger px-4 mx-2',
                    cancelButton: 'btn btn-secondary px-4 mx-2'
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

@push('css')
<style>
    /* Definición del color clínico personalizado */
    .btn-outline-clinica {
        color: #14b2c6;
        border-color: #14b2c6;
    }

    .btn-outline-clinica:hover, .btn-outline-clinica.active {
        background-color: #14b2c6 !important;
        border-color: #14b2c6 !important;
        color: white !important;
    }

    /* Asegurar que el botón no cambie de tamaño al cambiar el texto */
    #btn_filtro_incompleto {
        transition: all 0.3s ease;
        white-space: nowrap;
        overflow: hidden;
    }
    
    .table-hover tbody tr:hover {
        background-color: #f8fbff;
        transition: background-color 0.2s ease;
    }
    .badge-pill {
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