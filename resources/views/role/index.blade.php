@extends('adminlte::page')

@section('title')
    {{ config('adminlte.title') }}
    @hasSection('subtitle') | @yield('subtitle') @endif
@stop

{{-- Encabezado --}}
@section('content_header')
    <div class="container-fluid pt-4">
        <div class="row align-items-center">
            <div class="col-6 text-left">
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 1.6rem;">
                    <i class="fas fa-user-tag text-primary mr-2"></i> {{ __('Roles') }}
                </h1>
            </div>
            <div class="col-6 text-right">
                <a href="{{ route('role.create') }}" class="btn btn-primary shadow-sm px-3" style="border-radius: 50px; font-weight: bold;">
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
                    
                    {{-- Eliminamos el Alert de Bootstrap antiguo ya que usaremos SweetAlert2 --}}

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0 px-4 py-3 text-muted" style="width: 100px;"># ID</th>
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
                                                {{-- Se añade la clase form-eliminar --}}
                                                <form action="{{ route('role.destroy', $role->id) }}" method="POST" class="mb-0 form-eliminar">
                                                    <div class="btn-group">
                                                        <a class="btn btn-sm btn-light text-primary shadow-sm mr-1" 
                                                           href="{{ route('role.show', $role->id) }}" style="border-radius: 8px;">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                        <a class="btn btn-sm btn-light text-success shadow-sm mr-1" 
                                                           href="{{ route('role.edit', $role->id) }}" style="border-radius: 8px;">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                        @csrf
                                                        @method('DELETE')
                                                        {{-- Quitamos el onclick antiguo --}}
                                                        <button type="submit" class="btn btn-sm btn-light text-danger shadow-sm" style="border-radius: 8px;">
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

@section('footer')
<div class="float-right">Version: {{ config('app.version', '1.0.0') }}</div>
<strong>© 2025 - Consultorio El Buen Pastor. Desarrollado por Levi Ruiz y Erlin Silva.</strong>
@stop

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // 1. Manejo del borrado con SweetAlert2
        $('.form-eliminar').submit(function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: '¿Estás seguro?',
                text: "El rol se eliminará permanentemente del sistema.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-trash"></i> Sí, eliminar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true,
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

        // 2. Notificación de éxito dinámica
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