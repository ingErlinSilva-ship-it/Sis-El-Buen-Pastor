@extends('adminlte::page')

@section('title')
{{ config('adminlte.title') }}
@stop

@section('content')
<div class="container-fluid pt-4">

    {{-- 1. ENCABEZADO DE BIENVENIDA (Estilo Admin) --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow border-0"
                style="border-radius: 20px; background: linear-gradient(135deg, #0f766e 0%, #14b8a6 100%); color: white;">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="font-weight-bold mb-1">¡Bienvenido, Dr.
                                {{ explode(' ', trim(Auth::user()->nombre))[0] }}!</h1>
                            <p class="opacity-75 mb-3">Panel de Gestión Médica — Consultorio El Buen Pastor</p>
                            <div class="mt-2">
                                {{-- Botón para Editar Perfil de Usuario --}}
                                <a href="{{ route('usuario.edit', Auth::user()->id) }}"
                                    class="btn btn-light btn-sm font-weight-bold px-4 shadow-sm"
                                    style="border-radius: 50px; color: #0f766e;">
                                    <i class="fas fa-user-edit mr-2"></i> Editar mi Perfil
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4 text-right d-none d-md-block">
                            @if(Auth::user()->foto)
                                <img src="{{ asset('storage/fotos/' . Auth::user()->foto) }}" class="img-circle shadow-lg"
                                    alt="User Image"
                                    style="width: 110px; height: 110px; object-fit: cover; border: 4px solid rgba(255,255,255,0.4);">
                            @else
                                <div class="bg-white img-circle d-inline-flex align-items-center justify-content-center shadow-lg"
                                    style="width: 100px; height: 100px;">
                                    <i class="fas fa-user-md fa-3x text-teal"></i>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. INDICADORES DE GESTIÓN --}}
    <h5 class="mb-3 text-muted font-weight-bold text-uppercase" style="letter-spacing: 1px;">
        <i class="fas fa-briefcase-medical mr-2 text-teal"></i>Resumen de Actividad
    </h5>

    <div class="row">
        {{-- Citas de Hoy --}}
        <div class="col-lg-4 col-md-6 col-12">
            <div class="info-box shadow-sm border-0" style="border-radius: 15px;">
                <span class="info-box-icon bg-info elevation-1" style="border-radius: 12px;"><i
                        class="fas fa-calendar-check"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text text-muted font-weight-bold">Citas para Hoy</span>
                    <span class="info-box-number h4 mb-0">{{ $totalCitasHoy }}</span>
                    <a href="{{ route('cita.index') }}" class="small text-info font-weight-bold">Ver agenda completa <i
                            class="fas fa-chevron-right ml-1"></i></a>
                </div>
            </div>
        </div>

        {{-- Gestión de Pacientes --}}
        <div class="col-lg-4 col-md-6 col-12">
            <div class="info-box shadow-sm border-0" style="border-radius: 15px;">
                <span class="info-box-icon bg-purple elevation-1"
                    style="border-radius: 12px; color: white !important;"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text text-muted font-weight-bold">Expedientes</span>
                    <span class="info-box-number h4 mb-0">Pacientes</span>
                    <a href="{{ route('paciente.index') }}" class="small text-purple font-weight-bold">Ir a registros <i
                            class="fas fa-chevron-right ml-1"></i></a>
                </div>
            </div>
        </div>

        {{-- Ver Perfil Médico (Reemplaza Estatus) --}}
        <div class="col-lg-4 col-md-12 col-12">
            <div class="info-box shadow-sm border-0" style="border-radius: 15px;">
                <span class="info-box-icon bg-success elevation-1" style="border-radius: 12px;"><i
                        class="fas fa-id-card-alt"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text text-muted font-weight-bold">Perfil Profesional</span>
                    <span class="info-box-number h4 mb-0">Mi Información</span>
                    <a href="{{ route('medico.show', $miMedicoId) }}" class="small text-success font-weight-bold">Ver mi
                        Perfil Médico <i class="fas fa-chevron-right ml-1"></i></a>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. TABLA DE PRÓXIMOS PACIENTES --}}
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-header bg-white py-3 border-0">
                    <h3 class="card-title font-weight-bold text-dark">
                        <i class="fas fa-clock text-warning mr-2"></i> Próximos Pacientes por Atender
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 px-4 py-3" style="width: 150px;">Hora</th>
                                    <th class="border-0 py-3">Paciente</th>
                                    <th class="border-0 py-3 text-center">Motivo de Consulta</th>
                                    <th class="border-0 py-3 text-right px-4">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($citasHoy as $cita)
                                    <tr>
                                        <td class="px-4 align-middle">
                                            <span class="badge badge-pill badge-primary py-2 px-3 shadow-xs">
                                                <i class="far fa-clock mr-1"></i>
                                                {{ \Carbon\Carbon::parse($cita->hora)->format('h:i A') }}
                                            </span>
                                        </td>
                                        <td class="align-middle font-weight-bold">
                                            {{ $cita->paciente->usuario->nombre }} {{ $cita->paciente->usuario->apellido }}
                                        </td>
                                        <td class="align-middle text-center text-muted">
                                            {{ Str::limit($cita->motivo, 50) }}
                                        </td>
                                        <td class="text-right px-4 align-middle">
                                            <a href="{{ route('consultas.atender', $cita->id) }}"
                                                class="btn btn-outline-success btn-sm font-weight-bold px-3 shadow-sm"
                                                style="border-radius: 20px; border-width: 2px;">
                                                <i class="fas fa-notes-medical mr-1"></i> Atender
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="fas fa-mug-hot fa-3x mb-3 d-block opacity-50"></i>
                                                <p class="mb-0">No tiene citas pendientes para hoy.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@push('css')
    <style>
        .shadow-xs {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .badge-pill {
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .table-hover tbody tr:hover {
            background-color: #f8f9fa !important;
            transition: 0.3s;
        }

        .info-box {
            min-height: 90px;
        }

        .info-box-icon {
            border-radius: 12px;
        }

        .opacity-75 {
            opacity: 0.75;
        }

        .img-circle {
            border-radius: 50%;
        }

        .bg-purple {
            background-color: #6f42c1 !important;
        }

        .container-fluid {
            animation: fadeIn 0.6s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endpush
@stop

{{-- Pop-up Flotante de Usuarios Pendientes de Expediente --}}
@if(isset($notificaciones_count) && $notificaciones_count > 0)
<div id="notif-pendiente" style="position: fixed; bottom: 20px; right: 20px; z-index: 1050; width: 350px; animation: slideUp 0.5s ease-out;">
    <div class="card shadow-lg border-0" style="border-radius: 15px; background: white;">
        <div class="card-body p-3" style="border-left: 6px solid #dc3545 !important;"> {{-- Rojo para indicar acción pendiente --}}
            <div class="d-flex align-items-center">
                <div class="bg-light img-circle d-flex align-items-center justify-content-center mr-3" style="width: 50px; height: 50px;">
                    <i class="fas fa-user-clock text-danger fa-lg"></i>
                </div>
                <div>
                    <h6 class="font-weight-bold mb-0" style="color: #1e3a8a;">Registros Incompletos</h6>
                    <small class="text-muted">Hay <b>{{ $notificaciones_count }}</b> usuarios sin expediente clínico.</small>
                </div>
            </div>
            <div class="mt-3 d-flex justify-content-end">
                <button onclick="document.getElementById('notif-pendiente').remove()" class="btn btn-link btn-sm text-muted mr-2">Omitir</button>
                <a href="{{ route('paciente.create') }}?sin_expediente=true" class="btn btn-danger btn-sm px-3 rounded-pill shadow-sm font-weight-bold">
    <i class="fas fa-search mr-1"></i> Revisar Ahora
</a>
            </div>
        </div>
    </div>
</div>
@endif

@push('css')
<style>
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(100px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@push('css')
<style>
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(100px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@push('css')
<style>
/* Animación para el Pop-up flotante */
@keyframes slideIn {
    from { opacity: 0; transform: translateX(100%); }
    to { opacity: 1; transform: translateX(0); }
}

.toast-container {
    transition: all 0.3s ease;
}

.btn-xs {
    padding: 2px 8px;
    font-size: 0.75rem;
}
</style>
@endpush

@section('footer')
<div class="float-right">Versión: {{ config('app.version', '1.0.0') }}</div>
<strong>© 2026 - Consultorio El Buen Pastor. Desarrollado por Levi Ruiz y Erlin Silva.</strong>
@stop