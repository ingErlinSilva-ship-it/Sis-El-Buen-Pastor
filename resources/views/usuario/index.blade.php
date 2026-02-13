@extends('adminlte::page')

@section('title')
{{ config('adminlte.title') }}
@stop

{{-- Encabezado --}}
@section('content_header')
<div class="container-fluid pt-4">
    <div class="row align-items-center">
        <div class="col-6 text-left">
            <h1 class="m-0 text-dark font-weight-bold" style="font-size: 1.6rem;">
                
                @if(Auth::user()->rol_id == 1)
                <i class="fas fa-users text-primary mr-2"></i>
                {{ __('Usuarios') }}
                @else
                <i class="fas fa-user text-primary mr-2"></i>
                {{ __('Usuario') }}
                @endif
            </h1>
        </div>
        {{-- FILTRO: Solo Admin ve el botón de añadir --}}
        @if(Auth::user()->rol_id == 1)
            <div class="col-6 text-right">
                <a href="{{ route('usuario.create') }}" class="btn btn-invert-blue shadow-sm">
                    <i class="fas fa-plus mr-1"></i> {{ __('Añadir Nuevo Usuario') }}
                </a>
            </div>
        @endif
    </div>
</div>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                {{-- Card con bordes redondeados --}}
                <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                    
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0 px-4 py-3 text-muted" style="width: 50px;">No</th>
                                        <th class="border-0 py-3 text-muted">Usuario</th>
                                        <th class="border-0 py-3 text-muted text-center">Contacto</th>
                                        <th class="border-0 py-3 text-muted text-center">Estado</th>
                                        <th class="border-0 py-3 text-muted text-center">Rol de Acceso</th>
                                        <th class="border-0 py-3 text-right px-4 text-muted">{{ __('Acciones') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($usuarios as $usuario)
                                        <tr>
                                            <td class="align-middle px-4 text-muted">{{ ++$i }}</td>
                                            <td class="align-middle">
                                                <div class="d-flex align-items-center">
                                                    {{-- Foto circular con manejo de almacenamiento --}}
                                                    <div class="rounded-circle mr-3 d-flex align-items-center justify-content-center bg-light border shadow-sm" style="width: 45px; height: 45px; overflow: hidden;">
                                                        @if($usuario->foto)
                                                            <img src="{{ asset('storage/fotos/' . $usuario->foto) }}" alt="Foto" style="width: 100%; height: 100%; object-fit: cover;">
                                                        @else
                                                            <i class="fas fa-user text-muted"></i>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <span class="font-weight-bold text-dark d-block text-capitalize">{{ $usuario->nombre }} {{ $usuario->apellido }}</span>
                                                        <small class="text-muted">{{ $usuario->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center align-middle">
                                                <span class="text-muted small"><i class="fas fa-phone-alt mr-1"></i> {{ $usuario->celular ?? 'N/A' }}</span>
                                            </td>
                                            <td class="text-center align-middle">
                                                @if ($usuario->estado == 1)
                                                    <span class="badge badge-pill shadow-sm" style="background-color: #e8f5e9; color: #2e7d32; padding: 0.5em 1em; border: 1px solid #c8e6c9;">
                                                        <i class="fas fa-check-circle mr-1"></i> Activo
                                                    </span>
                                                @else
                                                    <span class="badge badge-pill shadow-sm" style="background-color: #ffebee; color: #c62828; padding: 0.5em 1em; border: 1px solid #ffcdd2;">
                                                        <i class="fas fa-times-circle mr-1"></i> Inactivo
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-center align-middle">
                                                @php
                                                    $roleName = $usuario->role?->nombre ?? 'Sin Rol';
                                                    $roleKey = strtolower($roleName);
                                                    $badgeClass = match (true) {
                                                        str_contains($roleKey, 'administrador') => 'badge-primary',
                                                        str_contains($roleKey, 'doctor') => 'badge-info',
                                                        str_contains($roleKey, 'paciente') => 'badge-warning',
                                                        default => 'badge-secondary',
                                                        };
                                                    @endphp
                                                <span class="badge {{ $badgeClass }} shadow-sm" style="padding: 0.5em 1em; border-radius: 8px; min-width: 90px;">
                                                    {{ $roleName }}
                                                </span>
                                            </td>
                                            <td class="text-right align-middle px-4">
                                                <form action="{{ route('usuario.destroy', $usuario->id) }}" method="POST" class="mb-0 form-eliminar">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="d-flex justify-content-end">
                                                        
                                                        {{-- Ver --}}
                                                        <a class="btn btn-sm btn-invert-purple mx-1" 
                                                        href="{{ route('usuario.show', $usuario->id) }}" title="Ver Perfil">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    
                                                    {{-- Editar --}}
                                                    <a class="btn btn-sm btn-invert-success mx-1" 
                                                    href="{{ route('usuario.edit', $usuario->id) }}" title="Editar">
                                                    <i class="fa fa-edit"></i>
                                                </a>
            
                                                {{-- FILTRO: Solo Administradores (rol 1) pueden eliminar --}}
                                                @if(Auth::user()->rol_id == 1)
                                                <button type="submit" class="btn btn-sm btn-invert-danger mx-1" title="Eliminar Usuario">
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
                {!! $usuarios->withQueryString()->links('pagination::bootstrap-4') !!}
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
        $('.form-eliminar').submit(function(e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Eliminar usuario?',
                text: "Esta acción revocará todos los accesos de este usuario al sistema.",
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

        /* Ajuste para el botón de Crear */
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

        /* Efectos Generales */
        .table-hover tbody tr:hover {
            background-color: #f1f7ff !important;
            transition: background-color 0.2s ease;
        }

        /* añade una sombra muy leve a la fila resaltada */
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