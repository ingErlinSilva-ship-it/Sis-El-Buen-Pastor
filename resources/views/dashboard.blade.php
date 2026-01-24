@extends('adminlte::page')

@section('title')
{{ config('adminlte.title') }}
@hasSection('subtitle') | @yield('subtitle') @endif
@stop

@section('content_header')
@stop

@section('content')
<div class="container-fluid pt-3">

    {{-- 1. SECCIÓN DE ESTADÍSTICAS PARA ADMIN Y DOCTOR --}}
    @can('doctor')
        
        {{-- Título de sección solo para Admin --}}
        @can('administrador')
            <h5 class="mb-3 text-muted"><i class="fas fa-hospital-user mr-2"></i>Gestión Operativa</h5>
        @endcan

        <div class="row">
            {{-- Pacientes --}}
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info shadow">
                    <div class="inner">
                        <h3>{{ $totalPacientes ?? 0 }}</h3>
                        <p>Pacientes Registrados</p>
                    </div>
                    <div class="icon"><i class="fas fa-user-injured"></i></div>
                    <a href="{{ route('paciente.index') }}" class="small-box-footer">Ver lista <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            @can('administrador')
                {{-- Usuarios --}}
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success shadow">
                        <div class="inner">
                            <h3>{{ $totalUsuarios ?? 0 }}</h3>
                            <p>Usuarios del Sistema</p>
                        </div>
                        <div class="icon"><i class="fas fa-users-cog"></i></div>
                        <a href="{{ route('usuario.index') }}" class="small-box-footer">Gestionar <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                {{-- Médicos --}}
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning shadow">
                        <div class="inner">
                            <h3>{{ $totalMedicos ?? 0 }}</h3>
                            <p>Médicos</p>
                        </div>
                        <div class="icon"><i class="fas fa-stethoscope"></i></div>
                        <a href="{{ route('medico.index') }}" class="small-box-footer">Ver médicos <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            @endcan

            {{-- Citas --}}
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger shadow">
                    <div class="inner">
                        <h3>{{ $totalCitas ?? 0 }}</h3>
                        <p>
                            @if(Auth::user()->rol_id == 1)
                                Citas del Día
                            @elseif(Auth::user()->rol_id == 2)
                                Mis Citas de Hoy
                            @endif
                        </p>
                    </div>
                    <div class="icon"><i class="fas fa-calendar-alt"></i></div>
                    <a href="{{ route('cita.index') }}" class="small-box-footer">Ver Agenda <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>

        {{-- 2. SECCIÓN DE CONFIGURACIÓN (EXCLUSIVA ADMINISTRADOR) --}}
        @can('administrador')
            <hr class="my-4">
            <h5 class="mb-3 text-muted"><i class="fas fa-cogs mr-2"></i>Configuración y Catálogos</h5>
            <div class="row">
                {{-- Roles --}}
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-secondary shadow">
                        <div class="inner">
                            <h3>{{ $totalRoles ?? 0 }}</h3>
                            <p>Roles</p>
                        </div>
                        <div class="icon"><i class="fas fa-user-tag"></i></div>
                        <a href="{{ route('role.index') }}" class="small-box-footer">Configurar <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                {{-- Especialidades --}}
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-olive shadow" style="background-color: #3d9970 !important; color: white;">
                        <div class="inner">
                            <h3>{{ $totalEspecialidades ?? 0 }}</h3>
                            <p>Especialidades</p>
                        </div>
                        <div class="icon"><i class="fas fa-microscope"></i></div>
                        <a href="{{ route('especialidade.index') }}" class="small-box-footer" style="color: rgba(255,255,255,0.8) !important;">Ver más <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                {{-- Alergias --}}
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-teal shadow">
                        <div class="inner">
                            <h3>{{ $totalAlergias ?? 0 }}</h3>
                            <p>Alergias</p>
                        </div>
                        <div class="icon"><i class="fas fa-allergies"></i></div> {{-- Icono corregido --}}
                        <a href="{{ route('alergia.index') }}" class="small-box-footer">Catálogo <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                {{-- Enfermedades --}}
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-maroon shadow">
                        <div class="inner">
                            <h3>{{ $totalEnfermedades ?? 0 }}</h3>
                            <p>Enfermedades</p>
                        </div>
                        <div class="icon"><i class="fas fa-virus"></i></div>
                        <a href="{{ route('enfermedade.index') }}" class="small-box-footer">Catálogo <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
        @endcan
    @endcan

    {{-- 3. SECCIÓN DE BIENVENIDA (PARA DOCTORES Y PACIENTES) --}}
    @if(Auth::user()->rol_id == 2 || Auth::user()->rol_id == 3)
        <div class="row justify-content-center align-items-center" style="min-height: 40vh; margin-top: 20px;">
            <div class="col-md-8 text-center {{ Auth::user()->rol_id == 2 ? 'border-top pt-4' : '' }}">
                <h1 class="display-4 mb-2" style="color: #3da9f3; font-weight: bold;">Bienvenido, {{ Auth::user()->name }}</h1>
                <h2 class="display-5 mb-4" style="color: #0b4c81; font-weight: bold;">Consultorio El Buen Pastor</h2>

                @if(Auth::user()->rol_id == 3)
                    <p class="lead text-muted mb-5">Desde aquí puedes gestionar tus citas médicas de manera sencilla.</p>
                    <a href="{{ route('cita.index') }}" class="btn btn-primary btn-lg px-5 shadow">
                        <i class="fas fa-calendar-plus mr-2"></i> Agendar mi Cita
                    </a>
                @else
                    <p class="lead text-muted mb-4">Sistema Integral de Gestión de Consultas Médicas</p>
                @endif
            </div>
        </div>
    @endif
</div>
@stop

@section('footer')
<div class="float-right">Version: {{ config('app.version', '1.0.0') }}</div>
<strong>© 2025 - Consultorio El Buen Pastor. Desarrollado por Levi Ruiz y Erlin Silva.</strong>
@stop