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

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Create') }} Pacientes</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('paciente.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('paciente.form')

                        </form>
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

    // Seleccionamos el campo de cédula por su ID 
    const cedulaInput = document.getElementById('cedula');

    cedulaInput.addEventListener('input', function (e) {
        let value = e.target.value.replace(/[^0-9a-zA-Z]/g, ''); // Quitamos todo lo que no sea número o letra
        let formattedValue = '';

        if (value.length > 0) {
            formattedValue += value.substring(0, 3);
            if (value.length > 3) {
                formattedValue += '-' + value.substring(3, 9);
            }
            if (value.length > 9) {
                formattedValue += '-' + value.substring(9, 13);
            }
            if (value.length > 13) {
                formattedValue += value.substring(13, 14).toUpperCase(); // La última es la letra
            }
        }
        e.target.value = formattedValue.substring(0, 16); // Límite de caracteres de la cédula
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
