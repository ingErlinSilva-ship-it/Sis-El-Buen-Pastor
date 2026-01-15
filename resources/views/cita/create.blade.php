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
                        <span class="card-title">{{ __('Create') }} Citas</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('cita.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('cita.form')

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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.8/jquery.inputmask.min.js"></script>
<script>
$(document).ready(function() {
    // 1. CONFIGURACIÓN DE MÁSCARAS
    $('#buscar_cedula').inputmask("999-999999-9999A");
    $('#celular_paciente').inputmask("99999999", {placeholder: "",clearMaskOnLostFocus: true});

    // 2. LÓGICA DEL BUSCADOR DE CÉDULA
    $('#btn_consultar').on('click', function() {
        let cedula = $('#buscar_cedula').val();
        
        if (!$('#buscar_cedula').inputmask("isComplete")) {
            alert("Por favor, ingrese un número de cédula completo.");
            return;
        }

        $.get('/paciente/buscar-por-cedula/' + cedula)
            .done(function(data) {
                if (data.status === 'success') {
                    // CASO: PACIENTE EXISTE
                    $('#seccion_registro_nuevo').slideUp();
                    $('#seccion_paciente_existente').slideDown();

                    // RELLENAR LOS DOS CAMPOS NUEVOS
                    $('#nombre_paciente_ex').val(data.nombre);
                    $('#apellido_paciente_ex').val(data.apellido);
                    
                    $('#paciente_id_hidden').val(data.id);
                    $('.bloqueable').prop('disabled', false);

                    $('#medico_id_select').prop('disabled', false);
                    
                    alert("Paciente listo para agendar.");
                } else {
                    // CASO: PACIENTE NUEVO
                    if (confirm("El paciente con cédula " + cedula + " no existe. ¿Desea realizar un registro rápido?")) {
                        $('#seccion_paciente_existente').slideUp();
                        $('#seccion_registro_nuevo').slideDown();
                        
                        // Limpiamos ID previo y guardamos la cédula
                        $('#paciente_id_hidden').val(''); 
                        $('#cedula_buscada').val(cedula);
                        
                        $('#nombre_paciente').focus();
                        $('.bloqueable').prop('disabled', true); // Bloqueamos hasta validar
                    }
                }
            });
    });

    // 3. LÓGICA DE ESPECIALIDAD AUTOMÁTICA
    $('#medico_id_select').on('change', function() {
        let especialidad = $(this).find(':selected').data('especialidad');
        $('#especialidad_display').val(especialidad ? especialidad : '');
    });

    // 4. BOTÓN: VALIDAR NUEVO PACIENTE
    $('#btn_guardar_paciente_rapido').on('click', function() {
        let nombre = $('#nombre_paciente').val();
        let apellido = $('#apellido_paciente').val();
        let celular = $('#celular_paciente').val();

        if (nombre == "" || apellido == "" || celular.length !== 8) {
            alert("Error: Nombre, Apellido y Celular (8 dígitos) son obligatorios.");
            return;
        }

        // Habilitamos los campos para continuar con la cita
        $('.bloqueable').prop('disabled', false);
        $('#medico_id_select').prop('disabled', false);
        
        // Estética de validación
        $('#seccion_registro_nuevo input').prop('readonly', true);
        $(this).html('<i class="fas fa-check"></i> Paciente Validado')
               .removeClass('btn-primary')
               .addClass('btn-success')
               .prop('disabled', true);
        
        alert("Paciente validado correctamente. Proceda a llenar los datos de la cita.");
    });

    // 5. PREVENCIÓN DE CAMPOS VACÍOS AL ENVIAR
    // Habilita todos los campos justo antes del submit para que viajen los datos
    $('form').on('submit', function() {
        $('.bloqueable').prop('disabled', false);
        $('#medico_id_select').prop('disabled', false);
        // Si el select del paciente fuera disabled, lo habilitamos aquí también
    });
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
    /* Oculta la barra de búsqueda global de AdminLTE solo en esta vista */
    .navbar-search-block, 
    .form-inline .input-group {
        display: none !important;
    }

</style>
@endpush


