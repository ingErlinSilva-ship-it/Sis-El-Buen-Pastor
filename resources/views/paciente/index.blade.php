@extends('adminlte::page')

@section('title')
{{ config('adminlte.title') }}
@stop

@section('content_header')
    <div class="container-fluid pt-2">
        
        {{-- BARRA DE BÚSQUEDA Y FILTROS (Solo Admin/Doctor) --}}
        @if(Auth::user()->rol_id != 3)
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="input-group search-group shadow-xs">
                    <input type="text" id="search_nombre" class="form-control border-right-0"
                        placeholder="Escriba el nombre del paciente para buscar..."
                        style="border-radius: 10px 0 0 10px; height: 45px; border-width: 2px;">
                    
                    <div class="input-group-append">
                        <span class="input-group-text bg-white border-left-0"
                            style="border-radius: 0 10px 10px 0; border-width: 2px;">
                            <i class="fas fa-search text-primary"></i>
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-md-4 text-right">
                <button id="btn_filtro_incompleto" class="btn btn-outline-clinica shadow-sm font-weight-bold" style="border-radius: 10px; min-width: 220px;">
                    <i class="fas fa-file-medical-alt mr-1"></i> Expedientes Incompletos
                </button>
            </div>
        </div>
        @endif

        <div class="row align-items-center">
            <div class="col-6 text-left">
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 1.6rem;">
                    <i class="fas fa-user-injured text-primary mr-2"></i> 
                    {{ Auth::user()->rol_id == 3 ? __('Mi Perfil de Paciente') : __('Pacientes') }}
                </h1>
            </div>

            {{-- El botón Crear solo lo ve el Admin o Doctor --}}
            @if(Auth::user()->rol_id != 3)
            <div class="col-6 text-right">
                <a href="{{ route('paciente.create') }}" class="btn btn-invert-blue shadow-sm">
                    <i class="fas fa-plus mr-1"></i> {{ __('Crear Nuevo Paciente') }}
                </a>
            </div>
            @endif
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
                        $esIncompleto = !$paciente->fecha_nacimiento || !$paciente->tipo_sangre || !$paciente->direccion || (!$paciente->es_menor && !$paciente->cedula);
                        @endphp
                        
                        {{-- FILA ÚNICA CORREGIDA --}}
                        <tr class="fila-paciente {{ $esIncompleto ? 'incompleto' : '' }}">
                            <td class="align-middle px-4 text-muted">{{ ++$i }}</td>
                            <td class="align-middle">
                                <div class="d-flex align-items-center">
                                     <div class="rounded-circle mr-3 d-flex align-items-center justify-content-center bg-light border shadow-sm" style="width: 45px; height: 45px; overflow: hidden;">
                                        @if($paciente->usuario?->foto)
                                        <img src="{{ asset('storage/fotos/' . $paciente->usuario->foto) }}" alt="Foto" style="width: 100%; height: 100%; object-fit: cover;">
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
                                    @csrf @method('DELETE')
                                    <div class="d-flex justify-content-end">
                                        
                                        {{-- Ver: Todos lo ven --}}
                                        <a class="btn btn-sm btn-invert-purple mx-1" href="{{ route('paciente.show', $paciente->id) }}"
                                            title="Ver Expediente"><i class="fas fa-eye"></i>
                                        </a>
            
                                        {{-- Editar: Doctores, Admin--}}
                                        @if(Auth::user()->rol_id != 3 )
                                            <a class="btn btn-sm btn-invert-success mx-1" href="{{ route('paciente.edit', $paciente->id) }}"
                                                title="Editar Datos Personales">
                                                    <i class="fa fa-edit"></i>
                                            </a>
                                        @endif

                                        {{-- Eliminar: SOLO Administrador --}}
                                        @if(Auth::user()->rol_id == 1)
                                            <button type="submit" class="btn btn-sm btn-invert-danger mx-1" title="Eliminar">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        @endif
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

@section('footer')
    <div class="float-right">Version: {{ config('app.version', '1.0.0') }}</div>
    <strong>© 2025 - Consultorio El Buen Pastor. Desarrollado por Levi Ruiz y Erlin Silva.</strong>
@stop

@push('js')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            $(document).ready(function () {
                // Activar Tooltips
                $('[data-toggle="tooltip"]').tooltip();

                // 1. BUSCADOR POR NOMBRE EN TIEMPO REAL
                $("#search_nombre").on("keyup", function () {
                    var value = $(this).val().toLowerCase();
                    $(".fila-paciente").filter(function () {
                        $(this).toggle($(this).find('.nombre-paciente').text().toLowerCase().indexOf(value) > -1)
                    });
                });

                // 2. FILTRO DE EXPEDIENTES INCOMPLETOS
    let filtrandoIncompletos = false;

    $(document).on("click", "#btn_filtro_incompleto", function (e) {
        e.preventDefault();
        filtrandoIncompletos = !filtrandoIncompletos;
        const btn = $(this);

        if (filtrandoIncompletos) {
            // MODO: ACTIVO (Relleno Turquesa, Texto Blanco)
            btn.addClass('btn-clinica-on').removeClass('btn-outline-clinica');
            btn.html('<i class="fas fa-users mr-1"></i> Ver Todos');

            $(".fila-paciente").hide();
            $(".fila-paciente.incompleto").fadeIn(300);
        } else {
            // MODO: NORMAL (Borde Turquesa, Texto Turquesa)
            btn.removeClass('btn-clinica-on').addClass('btn-outline-clinica');
            btn.html('<i class="fas fa-file-medical-alt mr-1"></i> Expedientes Incompletos');

            $(".fila-paciente").fadeIn(300);
        }
    });

                $('.form-eliminar').submit(function (e) {
                    e.preventDefault();
                    Swal.fire({
                        title: '¿Eliminar paciente?',
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

@push('css')
<style>
    /* 1. ESTADO NORMAL */
    .btn-outline-clinica {
        background-color: #ffffff !important;
        color: #14b2c6 !important;
        border: 2px solid #14b2c6 !important;
        border-radius: 10px;
        font-weight: bold;
        min-width: 220px;
        transition: transform 0.2s;
    }

    .btn-outline-clinica i { 
        color: #14b2c6 !important; 
    }

    /* 2. ESTADO SELECCIONADO */
    .btn-clinica-on, 
    .btn-clinica-on:hover {
        background-color: #14b2c6 !important;
        color: #ffffff !important;
        border: 2px solid #14b2c6 !important;
        border-radius: 10px;
        font-weight: bold;
        min-width: 220px;
        box-shadow: 0 4px 8px rgba(20, 178, 198, 0.3) !important;
    }
    
    .btn-clinica-on i, 
    .btn-clinica-on:hover i { 
        color: #ffffff !important; 
        display: inline-block !important;
    }

    /* Efecto al hacer clic */
    #btn_filtro_incompleto:active {
        transform: scale(0.96);
    }
</style>

<style>
    /* Estilo base del contenedor de búsqueda */
    .search-group {
        transition: all 0.3s ease;
        border-radius: 10px;
    }

    /* Estilo de los inputs dentro de la búsqueda */
    .search-group .form-control {
        border-color: #e0e0e0 !important;
        font-size: 1rem;
    }

    .search-group .input-group-text {
        border-color: #e0e0e0 !important;
        padding-right: 20px;
    }

    /* EFECTO FOCUS (Cuando el usuario hace clic para buscar) */
    .search-group:focus-within {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0, 123, 255, 0.1) !important;
    }

    .search-group:focus-within .form-control {
        border-color: #007bff !important;
        background-color: #ffffff !important;
    }

    .search-group:focus-within .input-group-text {
        border-color: #007bff !important;
        background-color: #ffffff !important;
    }

    /* Animación del icono de lupa al enfocar */
    .search-group:focus-within i {
        color: #0056b3 !important;
        transform: scale(1.1);
        transition: all 0.3s ease;
    }
</style>
@endpush

@push('css')
    <style>
        /* Estilos de Inversión */
        .btn-invert-blue,
        .btn-invert-purple,
        .btn-invert-success,
        .btn-invert-danger {
            background-color: #ffffff !important;
            border-radius: 8px !important;
            font-weight: 600 !important;
            transition: all 0.3s ease-in-out !important;
            border-width: 2px !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* Botón Crear */
        .btn-invert-blue {
            width: auto;
            height: auto;
            padding: 6px 20px !important;
            border: 2px solid #007bff !important;
            color: #007bff !important;
            border-radius: 50px !important;
        }

        .btn-invert-blue:hover {
            background-color: #007bff !important;
            color: #ffffff !important;
        }

        /* Colores de Inversión */
        .btn-invert-purple {
            border: 2px solid #8e44ad !important;
            color: #8e44ad !important;
        }

        .btn-invert-purple:hover {
            background-color: #8e44ad !important;
            color: #ffffff !important;
        }

        .btn-invert-success {
            border: 2px solid #28a745 !important;
            color: #28a745 !important;
        }

        .btn-invert-success:hover {
            background-color: #28a745 !important;
            color: #ffffff !important;
        }

        .btn-invert-danger {
            border: 2px solid #dc3545 !important;
            color: #dc3545 !important;
        }

        .btn-invert-danger:hover {
            background-color: #dc3545 !important;
            color: #ffffff !important;
        }

        /* Efectos de Fila y Botones */
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1) !important;
        }

        .btn:hover i {
            color: #ffffff !important;
        }

        .table-hover tbody tr:hover {
            background-color: #f1f7ff !important;
            transition: background-color 0.2s ease;
        }
    </style>
@endpush

@push('css')
    <style>
        /* Definición del color clínico personalizado */
        .btn-outline-clinica {
            color: #14b2c6;
            border-color: #14b2c6;
        }

        .btn-outline-clinica:hover,
        .btn-outline-clinica.active {
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
            background-color: #f1f7ff !important;
            transition: background-color 0.2s ease;
        }

        .table-hover tbody tr:hover td {
            box-shadow: inset 0 0 0 9999px rgba(0, 123, 255, 0.02);
        }

        .shadow-xs {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .badge {
            font-weight: 600;
            letter-spacing: 0.3px;
        }
    </style>
@endpush