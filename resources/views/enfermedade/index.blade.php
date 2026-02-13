@extends('adminlte::page')

@section('title')
{{ config('adminlte.title') }}
@stop

@section('content_header')
<div class="container-fluid pt-4">
    <div class="row align-items-center">
        <div class="col-6 text-left">
            <h1 class="m-0 text-dark font-weight-bold" style="font-size: 1.6rem;">
                <i class="fas fa-fw fa-virus text-primary mr-2"></i> {{ __('Catálogo de Enfermedades') }}
            </h1>
        </div>
        <div class="col-6 text-right">
            <a href="{{ route('enfermedade.create') }}" class="btn btn-invert-blue shadow-sm px-4">
                <i class="fas fa-plus mr-1"></i> {{ __('Añadir Nueva Enfermedad') }}
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
                                    <th class="border-0 px-4 py-3 text-muted" style="width: 80px;">No</th>
                                    <th class="border-0 py-3 text-muted">{{ __('Enfermedad') }}</th>
                                    <th class="border-0 py-3 text-muted">{{ __('Descripción / Notas') }}</th>
                                    <th class="border-0 py-3 text-right px-4 text-muted">{{ __('Acciones') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($enfermedades as $enfermedade)
                                    <tr>
                                        <td class="align-middle px-4 text-muted">{{ ++$i }}</td>
                                        <td class="align-middle">
                                            <span class="font-weight-bold text-dark">{{ $enfermedade->nombre }}</span>
                                        </td>
                                        <td class="align-middle">
                                            <span class="text-muted small d-inline-block text-truncate"
                                                style="max-width: 300px;">
                                                {{ $enfermedade->descripcion ?: 'Sin descripción registrada' }}
                                            </span>
                                        </td>
                                        <td class="text-right align-middle px-4">
                                            <form action="{{ route('enfermedade.destroy', $enfermedade->id) }}"
                                                method="POST" class="mb-0 form-eliminar">
                                                @csrf
                                                @method('DELETE')
                                                <div class="d-flex justify-content-end">

                                                    {{-- Ver: Púrpura --}}
                                                    <a class="btn btn-sm btn-invert-purple mr-1"
                                                        href="{{ route('enfermedade.show', $enfermedade->id) }}"
                                                        title="Ver Detalle">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    @if(Auth::user()->rol_id == 1)
                                                        {{-- Editar: Verde --}}
                                                        <a class="btn btn-sm btn-invert-success mr-1"
                                                            href="{{ route('enfermedade.edit', $enfermedade->id) }}"
                                                            title="Editar">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                        
                                                        {{-- Eliminar: Rojo --}}
                                                        <button type="submit" class="btn btn-sm btn-invert-danger"
                                                            title="Eliminar">
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
                {!! $enfermedades->withQueryString()->links('pagination::bootstrap-4') !!}
            </div>
        </div>
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
            $('.form-eliminar').submit(function (e) {
                e.preventDefault();
                Swal.fire({
                    title: '¿Eliminar enfermedad?',
                    text: "Esta acción no se puede deshacer en el catálogo médico.",
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
                    timerProgressBar: true,
                    customClass: { popup: 'rounded-lg' }
                });
            @endif
        });
    </script>
@endpush

@push('css')
    <style>
        /* Estilos de Inversión (Nomenclatura Púrpura/Azul/Verde/Rojo) */
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

        .btn-invert-blue {
            border: 2px solid #007bff !important;
            color: #007bff !important;
            border-radius: 50px !important;
        }

        .btn-invert-blue:hover {
            background-color: #007bff !important;
            color: #fff !important;
        }

        .btn-invert-purple {
            border: 2px solid #8e44ad !important;
            color: #8e44ad !important;
        }

        .btn-invert-purple i {
            color: #8e44ad !important;
        }

        .btn-invert-purple:hover,
        .btn-invert-purple:hover i {
            background-color: #8e44ad !important;
            color: #fff !important;
        }

        .btn-invert-success {
            border: 2px solid #28a745 !important;
            color: #28a745 !important;
        }

        .btn-invert-success:hover {
            background-color: #28a745 !important;
            color: #fff !important;
        }

        .btn-invert-danger {
            border: 2px solid #dc3545 !important;
            color: #dc3545 !important;
        }

        .btn-invert-danger:hover {
            background-color: #dc3545 !important;
            color: #fff !important;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1) !important;
        }

        .table-hover tbody tr:hover {
            background-color: #fcfaff;
            transition: background-color 0.2s ease;
        }
    </style>
@endpush