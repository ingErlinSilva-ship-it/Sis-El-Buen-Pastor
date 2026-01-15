@extends('adminlte::page')

{{-- Extend and customize the browser title --}}

@section('title')
    {{ config('adminlte.title') }}
    @hasSection('subtitle') | @yield('subtitle') @endif
@stop

{{-- Extend and customize the page content header --}}

@section('content_header')
    @hasSection('content_header_title')
        <h1 class="text-muted">
            @yield('content_header_title')

            @hasSection('content_header_subtitle')
                <small class="text-dark">
                    <i class="fas fa-xs fa-angle-right text-muted"></i>
                    @yield('content_header_subtitle')
                </small>
            @endif
        </h1>
    @endif
@stop

{{-- Rename section content to content_body --}}

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">Información del Médico</span>
                        </div>
                        <div class="ml-auto">
                            <a class="btn btn-primary btn-sm" href="{{ route('medico.index') }}">
                                <i class="fas fa-arrow-left"></i> Regresar
                            </a>
                        </div>
                    </div>

                    <div class="card-body bg-white">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><strong>Nombre</strong></label>
                                    <input type="text" class="form-control" value="{{ $medico->usuario->nombre }} " readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><strong>Apellido</strong></label>
                                    <input type="text" class="form-control" value="{{ $medico->usuario->apellido }} " readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><strong>Especialidad</strong></label>
                                    <input type="text" class="form-control" value="{{ $medico->especialidade->nombre }}" readonly >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><strong>Codigo Minsa</strong></label>
                                    <input type="text" class="form-control" value="{{ $medico->codigo_minsa }}" readonly >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><strong>Descripción</strong></label>
                                    <input type="text" class="form-control" value="{{ $medico->descripcion }}" readonly >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop

{{-- Create a common footer --}}

@section('footer')
    <div class="float-right">
        Version: {{ config('app.version', '1.0.0') }}
    </div>

    <strong>
        <a href="{{ config('app.company_url', '#') }}">
            {{ config('app.company_name', '© 2025 - Sistema web con asistente virtual para gestión de consultas médicas. Desarrollado por Levi Ruiz y Erlin Silva.') }}
        </a>
    </strong>
@stop

{{-- Add common Javascript/Jquery code --}}

@push('js')
<script>

    $(document).ready(function() {
        // Add your common script logic here...
    });

</script>
@endpush

{{-- Add common CSS customizations --}}

@push('css')
<style type="text/css">

    /*
    {{-- You can add AdminLTE customizations here --}}
    .card-header {
        border-bottom: none;
    }
    .card-title {
        font-weight: 600;
    }
    */

</style>
@endpush