@extends('adminlte::page')

@section('title', 'Pacientes | ' . config('adminlte.title'))

{{-- Encabezado con estilo unificado --}}
@section('content_header')
    <div class="container-fluid pt-2">
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
    <div class="row">
        <div class="col-sm-12">
            {{-- Card moderna con bordes redondeados al estilo de Usuarios --}}
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
                                    <tr>
                                        <td class="align-middle px-4 text-muted">{{ ++$i }}</td>
                                        <td class="align-middle">
                                            <div class="d-flex align-items-center">
                                                {{-- Foto circular--}}
                                                <div class="rounded-circle mr-3 d-flex align-items-center justify-content-center bg-light border shadow-sm" style="width: 45px; height: 45px; overflow: hidden;">
                                                    @if($paciente->usuario?->foto)
                                                        <img src="{{ asset('storage/'.$paciente->usuario->foto) }}" alt="Foto" style="width: 100%; height: 100%; object-fit: cover;">
                                                    @else
                                                        <i class="fas fa-user text-muted"></i>
                                                    @endif
                                                </div>
                                                <div>
                                                    <span class="font-weight-bold text-dark d-block text-capitalize">
                                                        {{ $paciente->usuario?->nombre }} {{ $paciente->usuario?->apellido }}
                                                    </span>
                                                        <small class="text-muted">
                                                            @if($paciente->fecha_nacimiento)
                                                                {{ \Carbon\Carbon::parse($paciente->fecha_nacimiento)->age }} años
                                                            @else
                                                                <span class="text-danger font-weight-bold">N/A</span>
                                                            @endif
                                                            | {{ $paciente->usuario?->email }}
                                                        </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center align-middle">
                                            {{-- Badge de Tipo de Sangre --}}
                                            <span class="badge badge-pill shadow-sm mb-1" style="background-color: #ffebee; color: #c62828; padding: 0.5em 1em; border: 1px solid #ffcdd2;">
                                                <i class="fas fa-tint mr-1"></i> {{ $paciente->tipo_sangre }}
                                            </span>
                                            <br>
                                            <small class="text-muted"><i class="fas fa-calendar-alt mr-1"></i> {{ \Carbon\Carbon::parse($paciente->fecha_nacimiento)->format('d/m/Y') }}</small>
                                        </td>
                                        <td class="text-center align-middle">
                                            <span class="text-muted small font-weight-bold">{{ $paciente->cedula }}</span>
                                        </td>
                                        <td class="align-middle text-muted small" style="max-width: 200px;">
                                            {{ Str::limit($paciente->direccion, 40) }}
                                        </td>
                                        <td class="text-right align-middle px-4">
                                            <form action="{{ route('paciente.destroy', $paciente->id) }}" method="POST" class="mb-0 form-eliminar">
                                                <div class="btn-group">
                                                    {{-- BOTÓN ESPECIAL PARA EL EXPEDIENTE --}}
                                                    <a class="btn btn-sm btn-light text-primary shadow-sm mr-1"
                                                       href="{{ route('paciente.show', $paciente->id) }}" style="border-radius: 8px;" title="Ver Expediente Clínico">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @can('doctor')
                                                        <a class="btn btn-sm btn-light text-success shadow-sm mr-1" 
                                                           href="{{ route('paciente.edit', $paciente->id) }}" style="border-radius: 8px;" title="Editar">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                    @endcan
                                                    @csrf
                                                    @method('DELETE')
                                                    @can('administrador')
                                                        <button type="submit" class="btn btn-sm btn-light text-danger shadow-sm" style="border-radius: 8px;" title="Eliminar Paciente">
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
    </div>
</div>
@stop

@section('footer')
    <div class="float-right">Version: {{ config('app.version', '1.0.0') }}</div>
    <strong>© 2025 - Consultorio El Buen Pastor. Desarrollado por Levi Ruiz y Erlin Silva.</strong>
@stop

{{-- Usamos el mismo JS de Usuarios para las alertas bonitas --}}
@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
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
    });
</script>
@endpush

@push('css')
<style>
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