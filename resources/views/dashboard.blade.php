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
    <div class="container-fluid pt-4">
    <div class="row justify-content-center align-items-center" style="min-height: 70vh;">
        <div class="col-md-8 text-center">
            {{-- Mensaje de Bienvenida --}}
            <h1 class="display-4 mb-4" style="color: #3da9f3; font-weight: bold;">Bienvenido</h1>
            <h1 class="display-4 mb-4" style="color: #0b4c81; font-weight: bold;">Consultorio El Buen Pastor</h1>
            <p class="lead text-muted mb-5">Sistema Integral de Gestión de Consultas Médicas</p>


        </div>
    </div>
</div>
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