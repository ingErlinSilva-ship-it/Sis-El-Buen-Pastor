@extends('adminlte::page')

@section('title')
{{ config('adminlte.title') }} | Dashboard Admin
@stop

@section('content')
<div class="container-fluid pt-4">

    {{-- 1. ENCABEZADO DE BIENVENIDA --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow border-0" style="border-radius: 20px; background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); color: white;">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="font-weight-bold mb-1">¡Bienvenido, {{ Auth::user()->nombre }}!</h1>
                            <p class="opacity-75 mb-3">Panel de Control Administrativo — Gestión Integral</p>
                            <a href="{{ route('usuario.edit', Auth::user()->id) }}" class="btn btn-light btn-sm font-weight-bold px-4 shadow-sm" style="border-radius: 50px; color: #1e3a8a;">
                                <i class="fas fa-user-edit mr-2"></i> Editar mi Perfil
                            </a>
                        </div>
                        <div class="col-md-4 text-right d-none d-md-block">
                            @if(Auth::user()->foto)
                                <img src="{{ asset('storage/' . Auth::user()->foto) }}" class="img-circle shadow-lg" alt="User Image" style="width: 110px; height: 110px; object-fit: cover; border: 4px solid rgba(255,255,255,0.4);">
                            @else
                                <div class="bg-white img-circle d-inline-flex align-items-center justify-content-center shadow-lg" style="width: 100px; height: 100px;">
                                    <i class="fas fa-user-shield fa-3x text-primary"></i>
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
        <i class="fas fa-chart-pie mr-2 text-primary"></i>Métricas del Consultorio
    </h5>
    
    <div class="row">
        {{-- Pacientes --}}
        <div class="col-lg-3 col-6">
            <div class="info-box shadow-sm border-0" style="border-radius: 15px;">
                <span class="info-box-icon bg-info elevation-1" style="border-radius: 12px;"><i class="fas fa-user-injured"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text text-muted font-weight-bold">Pacientes</span>
                    <span class="info-box-number h4 mb-0">{{ $totalPacientes ?? 0 }}</span>
                    <a href="{{ route('paciente.index') }}" class="small text-info font-weight-bold">Ver todos <i class="fas fa-chevron-right ml-1"></i></a>
                </div>
            </div>
        </div>

        {{-- Usuarios --}}
        <div class="col-lg-3 col-6">
            <div class="info-box shadow-sm border-0" style="border-radius: 15px;">
                <span class="info-box-icon bg-success elevation-1" style="border-radius: 12px;"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text text-muted font-weight-bold">Usuarios</span>
                    <span class="info-box-number h4 mb-0">{{ $totalUsuarios ?? 0 }}</span>
                    <a href="{{ route('usuario.index') }}" class="small text-success font-weight-bold">Gestionar <i class="fas fa-chevron-right ml-1"></i></a>
                </div>
            </div>
        </div>

        {{-- Médicos --}}
        <div class="col-lg-3 col-6">
            <div class="info-box shadow-sm border-0" style="border-radius: 15px;">
                <span class="info-box-icon bg-warning elevation-1" style="border-radius: 12px; color: white !important;"><i class="fas fa-stethoscope"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text text-muted font-weight-bold">Médicos</span>
                    <span class="info-box-number h4 mb-0">{{ $totalMedicos ?? 0 }}</span>
                    <a href="{{ route('medico.index') }}" class="small text-warning font-weight-bold">Cuerpo médico <i class="fas fa-chevron-right ml-1"></i></a>
                </div>
            </div>
        </div>

        {{-- Citas --}}
        <div class="col-lg-3 col-6">
            <div class="info-box shadow-sm border-0" style="border-radius: 15px;">
                <span class="info-box-icon bg-danger elevation-1" style="border-radius: 12px;"><i class="fas fa-calendar-alt"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text text-muted font-weight-bold">Agenda</span>
                    <span class="info-box-number h4 mb-0">{{ $totalCitas ?? 0 }}</span>
                    <a href="{{ route('cita.index') }}" class="small text-danger font-weight-bold">Ver citas <i class="fas fa-chevron-right ml-1"></i></a>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. SECCIÓN DE CATÁLOGOS CON COLOR --}}
    <h5 class="mb-3 mt-4 text-muted font-weight-bold text-uppercase" style="letter-spacing: 1px;">
        <i class="fas fa-th-large mr-2 text-secondary"></i>Configuración y Catálogos
    </h5>

    <div class="row">
        {{-- Roles --}}
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card h-100 border-0 shadow-sm text-white card-hover" style="border-radius: 15px; background: linear-gradient(135deg, #6c757d 0%, #495057 100%);">
                <div class="card-body text-center p-4">
                    <i class="fas fa-key fa-3x mb-3"></i>
                    <h5 class="font-weight-bold">Roles</h5>
                    <p class="small opacity-75 mb-4">Gestión de permisos y niveles de acceso.</p>
                    <a href="{{ route('role.index') }}" class="btn btn-light btn-block btn-sm font-weight-bold rounded-pill">Configurar</a>
                </div>
            </div>
        </div>

        {{-- Especialidades --}}
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card h-100 border-0 shadow-sm text-white card-hover" style="border-radius: 15px; background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);">
                <div class="card-body text-center p-4">
                    <i class="fas fa-microscope fa-3x mb-3"></i>
                    <h5 class="font-weight-bold">Especialidades</h5>
                    <p class="small opacity-75 mb-4">Ramas médicas del consultorio.</p>
                    <a href="{{ route('especialidade.index') }}" class="btn btn-light btn-block btn-sm font-weight-bold rounded-pill">Gestionar</a>
                </div>
            </div>
        </div>

        {{-- Alergias --}}
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card h-100 border-0 shadow-sm text-white card-hover" style="border-radius: 15px; background: linear-gradient(135deg, #17a2b8 0%, #117a8b 100%);">
                <div class="card-body text-center p-4">
                    <i class="fas fa-allergies fa-3x mb-3"></i>
                    <h5 class="font-weight-bold">Alergias</h5>
                    <p class="small opacity-75 mb-4">Catálogo de reacciones médicas.</p>
                    <a href="{{ route('alergia.index') }}" class="btn btn-light btn-block btn-sm font-weight-bold rounded-pill">Ver Catálogo</a>
                </div>
            </div>
        </div>

        {{-- Enfermedades --}}
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card h-100 border-0 shadow-sm text-white card-hover" style="border-radius: 15px; background: linear-gradient(135deg, #dc3545 0%, #85144b 100%);">
                <div class="card-body text-center p-4">
                    <i class="fas fa-virus fa-3x mb-3"></i>
                    <h5 class="font-weight-bold">Enfermedades</h5>
                    <p class="small opacity-75 mb-4">Registro de patologías comunes.</p>
                    <a href="{{ route('enfermedade.index') }}" class="btn btn-light btn-block btn-sm font-weight-bold rounded-pill">Ver Catálogo</a>
                </div>
            </div>
        </div>
    </div>

</div>
@stop

@push('css')
<style>
    /* Efecto de elevación para las tarjetas de catálogo */
    .card-hover {
        transition: all 0.3s ease;
    }
    .card-hover:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.15) !important;
    }
    
    /* Estilo para los info-boxes de arriba */
    .info-box {
        min-height: 90px;
    }
    .opacity-75 {
        opacity: 0.75;
    }
    .img-circle {
        border-radius: 50%;
    }
    .rounded-pill {
        border-radius: 50px !important;
    }
    
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
<div class="float-right">Versión: {{ config('app.version', '1.0.0') }}</div>
<strong>© 2025 - Consultorio El Buen Pastor. Desarrollado por Levi Ruiz y Erlin Silva.</strong>
@stop