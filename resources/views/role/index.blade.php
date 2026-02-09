@extends('adminlte::page')

@section('title')
{{ config('adminlte.title') }}
@stop

@section('content_header')
    <div class="container-fluid pt-4">
        <div class="row align-items-center">
            <div class="col-6 text-left">
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 1.6rem;">
                    {{-- Icono de cabecera ahora en Morado --}}
                    <i class="fas fa-key text-primary mr-2"></i> {{ __('Roles') }}
                </h1>
            </div>
            <div class="col-6 text-right">
                <a href="{{ route('role.create') }}" class="btn btn-invert-blue shadow-sm px-4">
                    <i class="fas fa-plus mr-1"></i> {{ __('Añadir Nuevo Rol') }}
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
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0 px-4 py-3 text-muted" style="width: 100px;">No</th>
                                        <th class="border-0 py-3 text-muted">{{ __('Nombre del Rol') }}</th>
                                        <th class="border-0 py-3 text-right px-4 text-muted">{{ __('Acciones') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($roles as $role)
                                        <tr>
                                            <td class="align-middle px-4 text-muted">{{ $loop->iteration }}</td>
                                            <td class="align-middle">
                                                <span class="font-weight-bold text-dark">{{ $role->nombre }}</span>
                                            </td>
                                            <td class="text-right align-middle px-4">
                                                <form action="{{ route('role.destroy', $role->id) }}" method="POST" class="mb-0 form-eliminar">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="btn-group">
                                                        {{-- Botón Ver --}}
                                                        <a class="btn btn-sm btn-invert-purple mr-1" 
                                                           href="{{ route('role.show', $role->id) }}" title="Ver Detalle">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                        {{-- Botón Editar --}}
                                                        <a class="btn btn-sm btn-invert-success mr-1" 
                                                           href="{{ route('role.edit', $role->id) }}" title="Editar">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                        {{-- Botón Eliminar --}}
                                                        <button type="submit" class="btn btn-sm btn-invert-danger" title="Eliminar">
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
                
                <div class="mt-4 d-flex justify-content-center">
                    {!! $roles->withQueryString()->links('pagination::bootstrap-4') !!}
                </div>
            </div>
        </div>
    </div>
@stop

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Alerta de confirmación para eliminar
        $('.form-eliminar').submit(function(e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Estás seguro?',
                text: "El rol se eliminará permanentemente.",
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

        // Alerta de Éxito (Captura el 'success' del controlador)
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: '¡Operación Exitosa!',
                text: '{{ session("success") }}',
                showConfirmButton: false,
                timer: 2500,
                timerProgressBar: true,
                customClass: { popup: 'rounded-lg' }
            });
        @endif
    });
</script>
@endpush

@push('css')
    <style>
        .text-purple-custom {
            color: #8e44ad !important;
        }

        /* Estilos Base de Inversión */
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

        /* Inversión Añadir */
        .btn-invert-blue {
            border: 2px solid #007bff !important;
            color: #007bff !important;
            border-radius: 50px !important;
        }

        .btn-invert-blue:hover {
            background-color: #007bff !important;
            color: #ffffff !important;
        }

        /* Inversión Ver */
        .btn-invert-purple {
            border: 2px solid #8e44ad !important;
            color: #8e44ad !important;
        }

        .btn-invert-purple:hover {
            background-color: #8e44ad !important;
            color: #ffffff !important;
        }

        /* Inversión Editar */
        .btn-invert-success {
            border: 2px solid #28a745 !important;
            color: #28a745 !important;
        }

        .btn-invert-success:hover {
            background-color: #28a745 !important;
            color: #ffffff !important;
        }

        /* Inversión Eliminar */
        .btn-invert-danger {
            border: 2px solid #dc3545 !important;
            color: #dc3545 !important;
        }

        .btn-invert-danger:hover {
            background-color: #dc3545 !important;
            color: #ffffff !important;
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