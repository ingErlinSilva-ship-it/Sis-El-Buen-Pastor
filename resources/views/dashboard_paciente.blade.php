@extends('adminlte::page')

@section('title')
{{ config('adminlte.title') }} | Mi Panel
@stop

@section('content')
<div class="container-fluid pt-4">

    {{-- 1. ENCABEZADO DE BIENVENIDA (Estilo Premium) --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow border-0" style="border-radius: 20px; background: linear-gradient(135deg, #1e40af 0%, #0891b2 100%); color: white;">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="font-weight-bold mb-1">¡Bienvenido, {{ Auth::user()->nombre }}!</h1>
                            <p class="opacity-75 mb-3">Tu salud es nuestra prioridad — Consultorio El Buen Pastor</p>
                            <div class="mt-2">
                                <a href="{{ route('usuario.edit', Auth::user()->id) }}" class="btn btn-light btn-sm font-weight-bold px-4 shadow-sm" style="border-radius: 50px; color: #1e40af;">
                                    <i class="fas fa-user-cog mr-2"></i> Editar mi Cuenta
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4 text-right d-none d-md-block">
                            @if(Auth::user()->foto)
                                <img src="{{ asset('storage/' . Auth::user()->foto) }}" class="img-circle shadow-lg" alt="User Image" style="width: 110px; height: 110px; object-fit: cover; border: 4px solid rgba(255,255,255,0.4);">
                            @else
                                <div class="bg-white img-circle d-inline-flex align-items-center justify-content-center shadow-lg" style="width: 100px; height: 100px;">
                                    <i class="fas fa-hand-holding-heart fa-3x text-primary"></i>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. ACCESOS RÁPIDOS (Estilo Info-Box) --}}
    <h5 class="mb-3 text-muted font-weight-bold text-uppercase" style="letter-spacing: 1px;">
        <i class="fas fa-th-large mr-2 text-primary"></i>Resumen General
    </h5>
    
    <div class="row">
        {{-- Mi Expediente --}}
{{-- Mi Expediente --}}
<div class="col-lg-6 col-md-6 col-12">
    <div class="info-box shadow-sm border-0" style="border-radius: 15px;">
        <span class="info-box-icon bg-primary elevation-1" style="border-radius: 12px;">
            <i class="fas fa-file-medical"></i>
        </span>
        <div class="info-box-content">
            <span class="info-box-text text-muted font-weight-bold">Mi Expediente</span>

            @if(Auth::user()->paciente)
                {{-- Caso 1: Tiene cuenta de paciente vinculada --}}
                <span class="info-box-number h5 mb-0">Consultar datos médicos</span>
                <a href="{{ route('paciente.show', Auth::user()->paciente->id) }}" class="small text-primary font-weight-bold">
                    Ver mi información <i class="fas fa-chevron-right ml-1"></i>
                </a>
            @else
                {{-- Caso 2: El mensaje que querías si no tiene cuenta de paciente --}}
                <span class="info-box-number h6 mb-0 text-danger font-weight-bold">
                    <i class="fas fa-exclamation-circle mr-1"></i> Sin cuenta de paciente
                </span>
                <p class="small text-muted mb-1">Aún no se le ha creado una cuenta de paciente vinculada.</p>
                <a href="{{ route('usuario.edit', Auth::user()->id) }}" class="small text-secondary font-weight-bold">
                    Ver mi perfil de usuario <i class="fas fa-chevron-right ml-1"></i>
                </a>
            @endif
        </div>
    </div>
</div>

        {{-- Mis Citas --}}
        <div class="col-lg-6 col-md-6 col-12">
            <div class="info-box shadow-sm border-0" style="border-radius: 15px;">
                <span class="info-box-icon bg-info elevation-1" style="border-radius: 12px;"><i class="fas fa-calendar-alt"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text text-muted font-weight-bold">Mis Citas</span>
                    <span class="info-box-number h5 mb-0">Citas registradas: {{ $totalCitas }}</span>
                    <a href="{{ route('cita.index') }}" class="small text-info font-weight-bold">Ir a mi calendario <i class="fas fa-chevron-right ml-1"></i></a>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. DETALLE DE PRÓXIMA CITA --}}
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-header bg-white py-3 border-0">
                    <h3 class="card-title font-weight-bold text-dark">
                        <i class="fas fa-clock text-warning mr-2"></i> Mi Próxima Cita
                    </h3>
                </div>
                <div class="card-body">
                    @if($proximaCita)
                        <div class="p-4" style="border-radius: 15px; background-color: #f8fbff; border: 1px solid #e3f2fd;">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <div class="bg-white shadow-sm d-flex align-items-center justify-content-center rounded-circle" style="width: 70px; height: 70px;">
                                        <i class="fas fa-calendar-check fa-2x text-primary"></i>
                                    </div>
                                </div>
                                <div class="col">
                                    <h4 class="font-weight-bold mb-1 text-primary">
                                        {{ \Carbon\Carbon::parse($proximaCita->fecha)->translatedFormat('d \d\e F, Y') }}
                                    </h4>
                                    <p class="mb-0 text-muted">
                                        <i class="far fa-clock mr-1"></i> <b>Hora:</b> {{ \Carbon\Carbon::parse($proximaCita->hora)->format('h:i A') }}
                                    </p>
                                    <p class="mb-0 text-muted">
                                        <i class="fas fa-user-md mr-1"></i> <b>Médico:</b> {{ $proximaCita->medico->usuario->nombre }} {{ $proximaCita->medico->usuario->apellido }}
                                    </p>
                                </div>
                                <div class="col-md-3 text-right mt-3 mt-md-0">
                                    <a href="{{ route('cita.index') }}" class="btn btn-primary btn-block rounded-pill shadow-sm">
                                        Ver detalles
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-minus fa-3x mb-3 d-block text-muted opacity-50"></i>
                            <h5 class="text-muted">No tienes citas programadas próximamente.</h5>
                            <a href="{{ route('cita.create') }}" class="btn btn-outline-primary mt-3 px-4 rounded-pill">
                                <i class="fas fa-plus mr-1"></i> Agendar una ahora
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@push('css')
<style>
    /* Estilos de Info-box */
    .info-box { min-height: 95px; transition: transform 0.3s ease; }
    .info-box:hover { transform: translateY(-5px); }
    .info-box-icon { border-radius: 12px; }

    /* General */
    .img-circle { border-radius: 50%; }
    .opacity-75 { opacity: 0.75; }
    .rounded-pill { border-radius: 50px !important; }
    
    /* Animación de entrada suave */
    .container-fluid {
        animation: fadeIn 0.6s ease-in-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@section('footer')
<div class="float-right text-muted">Version: {{ config('app.version', '1.0.0') }}</div>
<strong>© 2026 - Consultorio El Buen Pastor. Desarrollado por Levi Ruiz y Erlin Silva.</strong>
@stop