@extends('adminlte::page')

{{-- Extend and customize the browser title --}}

@section('title')
    {{ config('adminlte.title') }}
    @hasSection('subtitle') | @yield('subtitle') @endif
@stop

{{-- Extend and customize the page content header --}}

@section('content_header')

@stop

{{-- Rename section content to content_body --}}

@section('content')
    <div class="container-fluid">
        <form method="POST" action="{{ route('paciente.update', $paciente->id) }}"  role="form" enctype="multipart/form-data">
            {{ method_field('PATCH') }}
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

        function toggleTutorSection(isInitialLoad = false) { // Añadimos este parámetro
            if (checkboxMenor.checked) {
                seccionTutor.style.display = 'block';
                
                // SOLO borramos si NO es la carga inicial
                if (!isInitialLoad) {
                    cedulaPaciente.value = ''; 
                }
                
                cedulaPaciente.readOnly = true;
                cedulaPaciente.style.backgroundColor = '#e9ecef';
            } else {
                seccionTutor.style.display = 'none';
                cedulaPaciente.readOnly = false;
                cedulaPaciente.style.backgroundColor = '#ffffff';
                
                if (!isInitialLoad) {
                    seccionTutor.querySelectorAll('input, select').forEach(el => el.value = '');
                }
            }
        }

        // Al cargar la página, pasamos 'true' para que no borre los datos existentes
        toggleTutorSection(true); 

        // Al cambiar manualmente, no pasamos nada (borrará si cambia de estado)
        checkboxMenor.addEventListener('change', () => toggleTutorSection(false));
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

