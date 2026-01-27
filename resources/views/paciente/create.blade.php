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
    <div class="container-fluid">
        <form method="POST" action="{{ route('paciente.store') }}"  role="form" enctype="multipart/form-data">
            @csrf

            @include('paciente.form')

        </form>
    </div>

@stop

{{-- Create a common footer --}}

@section('footer')
    <div class="float-right">Version: {{ config('app.version', '1.0.0') }}</div>
    <strong>© 2025 - Consultorio El Buen Pastor. Desarrollado por Levi Ruiz y Erlin Silva.</strong>
@stop

{{-- Add common Javascript/Jquery code --}}

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.8/jquery.inputmask.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkboxMenor = document.getElementById('es_menor');
        const seccionTutor = document.getElementById('seccion_tutor');
        const cedulaPaciente = document.getElementById('cedula'); // Tu campo de cédula actual

        // Máscara para la cédula del Paciente
        $('#cedula').inputmask("999-999999-9999A", {
            placeholder: "000-000000-0000X",
            definitions: {
                'A': {
                    validator: "[A-Za-z]",
                    casing: "upper"
                }
            }
        });

        // Máscara para la cédula del Tutor
        $('#tutor_cedula').inputmask("999-999999-9999A", {
            placeholder: "000-000000-0000X",
            definitions: {
                'A': {
                    validator: "[A-Za-z]",
                    casing: "upper"
                }
            }
        });

        // Función para mostrar/ocultar y BLOQUEAR cédula del paciente
        function toggleTutorSection() {
            if (checkboxMenor.checked) {
                // Caso: ES MENOR
                seccionTutor.style.display = 'block';
                
                // Bloqueamos la cédula del paciente y la limpiamos
                cedulaPaciente.value = ''; 
                cedulaPaciente.readOnly = true;
                cedulaPaciente.style.backgroundColor = '#e9ecef'; // Color gris (deshabilitado)
            } else {
                // Caso: ES MAYOR
                seccionTutor.style.display = 'none';
                
                // Desbloqueamos la cédula del paciente
                cedulaPaciente.readOnly = false;
                cedulaPaciente.style.backgroundColor = '#ffffff'; // Color blanco (activo)
                
                // Limpiar los campos del tutor si se desmarca
                seccionTutor.querySelectorAll('input, select').forEach(el => el.value = '');
            }
        }

        // Ejecutar al cargar la página (para Edit o errores de validación)
        toggleTutorSection();

        // Ejecutar cada vez que el usuario haga clic en el checkbox
        checkboxMenor.addEventListener('change', toggleTutorSection);
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
