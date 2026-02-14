@extends('adminlte::page')

@section('title')
{{ config('adminlte.title') }}
@stop

@section('content_header')
<div class="container-fluid pt-4">
    <div class="row align-items-center">
        <div class="col-6 text-left">
            <h1 class="m-0 text-dark font-weight-bold" style="font-size: 1.6rem;">
                <i class="fas fa-fw fa-stethoscope text-primary mr-2"></i>
                {{ Auth::user()->rol_id == 2 ? __('Médico') : __('Médicos') }}
            </h1>
        </div>
        <div class="col-6 text-right">
            {{-- Botón Añadir: Mantiene el AZUL de creación --}}
            @if(Auth::user()->rol_id == 1)
                <a href="{{ route('medico.create') }}" class="btn btn-invert-blue shadow-sm px-4">
                    <i class="fas fa-plus mr-1"></i> {{ __('Añadir Nuevo Médico') }}
                </a>
            @endif
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
                                    <th class="border-0 px-4 py-3 text-muted" style="width: 50px;">No</th>
                                    <th class="border-0 py-3 text-muted">Médico</th>
                                    <th class="border-0 py-3 text-muted text-center">Especialidad</th>
                                    <th class="border-0 py-3 text-muted text-center">Código MINSA</th>
                                    <th class="border-0 py-3 text-right px-4 text-muted">{{ __('Acciones') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($medicos as $medico)
                                    <tr>
                                        <td class="align-middle px-4 text-muted">{{ $loop->iteration }}</td>

                                        <td class="align-middle">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle mr-3 d-flex align-items-center justify-content-center bg-light border shadow-sm"
                                                    style="width: 45px; height: 45px; overflow: hidden;">
                                                    @if($medico->usuario && $medico->usuario->foto)
                                                        <img src="{{ asset('storage/' . $medico->usuario->foto) }}" alt="Foto"
                                                            style="width: 100%; height: 100%; object-fit: cover;">
                                                    @else
                                                        <i class="fas fa-user-md text-muted"></i>
                                                    @endif
                                                </div>
                                                <div>
                                                    <span class="font-weight-bold text-dark d-block text-capitalize">
                                                        {{ $medico->usuario->nombre ?? 'N/A' }}
                                                        {{ $medico->usuario->apellido ?? '' }}
                                                    </span>
                                                    <small class="text-muted">{{ $medico->usuario->email ?? 'Sin correo' }}</small>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="text-center align-middle">
                                            @php
                                                $espNombre = $medico->especialidade->nombre ?? 'General';
                                                $espLower = strtolower($espNombre);
                                                $color = match (true) {
                                                    str_contains($espLower, 'pediat') => ['bg' => '#E3F2FD', 'text' => '#1976D2', 'icon' => 'fa-baby'],
                                                    str_contains($espLower, 'gineco') => ['bg' => '#FCE4EC', 'text' => '#C2185B', 'icon' => 'fa-female'],
                                                    str_contains($espLower, 'cardio') => ['bg' => '#FFEBEE', 'text' => '#D32F2F', 'icon' => 'fa-heartbeat'],
                                                    str_contains($espLower, 'odont') => ['bg' => '#E0F2F1', 'text' => '#00796B', 'icon' => 'fa-tooth'],
                                                    str_contains($espLower, 'psico') => ['bg' => '#F3E5F5', 'text' => '#7B1FA2', 'icon' => 'fa-brain'],
                                                    str_contains($espLower, 'derma') => ['bg' => '#FFF3E0', 'text' => '#E65100', 'icon' => 'fa-allergies'],
                                                    default => ['bg' => '#F1F5F9', 'text' => '#475569', 'icon' => 'fa-stethoscope'],
                                                };
                                            @endphp
                                            <span class="badge badge-pill shadow-sm"
                                                style="background-color: {{ $color['bg'] }}; color: {{ $color['text'] }}; padding: 0.6em 1.2em; border: 1px solid rgba(0,0,0,0.05); font-size: 0.85rem;">
                                                <i class="fas {{ $color['icon'] }} mr-1" style="opacity: 0.7;"></i>
                                                {{ $espNombre }}
                                            </span>
                                        </td>

                                        <td class="text-center align-middle">
                                            <code class="font-weight-bold"
                                                style="font-size: 0.95rem; background: #f8fafc; padding: 4px 10px; border-radius: 6px; color: #0b4c81; border: 1px solid #e2e8f0;">
                                                {{ $medico->codigo_minsa }}
                                            </code>
                                        </td>

                                        <td class="text-right align-middle px-4">
                                            <form action="{{ route('medico.destroy', $medico->id) }}" method="POST" class="mb-0 form-eliminar">
                                                @csrf
                                                @method('DELETE')
                                                <div class="btn-group">

                                                    {{-- Ver --}}
                                                    <a class="btn btn-sm btn-invert-purple mr-1" href="{{ route('medico.show', $medico->id) }}" title="Ver">
                                                        <i class="fa fa-eye"></i>
                                                    </a>

                                                    {{-- Editar --}}
                                                    @if(Auth::user()->rol_id == 1 || (Auth::user()->rol_id == 2 && Auth::user()->id == $medico->usuario_id))
                                                        <a class="btn btn-sm btn-invert-success mr-1" href="{{ route('medico.edit', $medico->id) }}" title="Editar">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                    @endif

                                                    {{-- Eliminar --}}
                                                    @if(Auth::user()->rol_id == 1)
                                                        <button type="submit" class="btn btn-sm btn-invert-danger" title="Eliminar">
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
                {!! $medicos->withQueryString()->links('pagination::bootstrap-4') !!}
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
                title: '¿Eliminar Médico?',
                text: "Se revocará el acceso médico, pero el usuario permanecerá en el sistema.",
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
    .text-purple-custom { color: #8e44ad !important; }

    /* Estilos Base de Inversión */
    .btn-invert-blue, .btn-invert-purple, .btn-invert-success, .btn-invert-danger {
        background-color: #ffffff !important;
        border-radius: 8px !important;
        font-weight: 600 !important;
        transition: all 0.3s ease-in-out !important;
        border-width: 2px !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    /* Botón Añadir */
    .btn-invert-blue { 
        border: 2px solid #007bff !important; 
        color: #007bff !important; 
        border-radius: 50px !important; 
    }

    .btn-invert-blue:hover { 
        background-color: #007bff !important; 
        color: #ffffff !important; 
    }

    /* Botón Ver */
    .btn-invert-purple { 
        border: 2px solid #8e44ad !important; 
        color: #8e44ad !important; 
    }
    
    .btn-invert-purple:hover { 
        background-color: #8e44ad !important; 
        color: #ffffff !important; 
    }

    /* Botón Editar */
    .btn-invert-success { 
        border: 2px solid #28a745 !important; 
        color: #28a745 !important; 
    }

    .btn-invert-success:hover { 
        background-color: #28a745 !important; 
        color: #ffffff !important; 
    }

    /* Botón Eliminar */
    .btn-invert-danger { 
        border: 2px solid #dc3545 !important; 
        color: #dc3545 !important; 
    }
    
    .btn-invert-danger:hover { 
        background-color: #dc3545 !important; 
        color: #ffffff !important; 
    }

    .btn:hover { 
        transform: translateY(-2px); 
        box-shadow: 0 4px 8px rgba(0,0,0,0.1) !important; 
    }
    
    .btn:hover i { 
        color: #ffffff !important; 
    }

    .table-hover tbody tr:hover {
        background-color: #fcfaff;
        transition: background-color 0.2s ease;
    }
</style>
@endpush