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
        <div class="">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Update') }} Cita</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('cita.update', $cita->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
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
    // 1. CONFIGURACIÓN INICIAL (Máscaras)
    $('#buscar_cedula').inputmask("999-999999-9999A");
    $('#celular_paciente').inputmask("99999999", {placeholder: "", clearMaskOnLostFocus: true});

    // 2. LÓGICA DE AUTO-CARGA
    let pacienteIdExistente = $('#paciente_id_hidden').val();

    if (pacienteIdExistente) {
        // 1. Precargar Cédula
        $('#buscar_cedula').val("{{ $cita->paciente->cedula ?? '' }}");
        
        // 2. Mostrar sección de paciente y rellenar nombres
        $('#seccion_paciente_existente').show();
        $('#nombre_paciente_ex').val("{{ $cita->paciente->usuario->nombre ?? '' }}");
        $('#apellido_paciente_ex').val("{{ $cita->paciente->usuario->apellido ?? '' }}");
        
        // 3. Seleccionar Médico y Habilitar campos
        $('#medico_id_select').val("{{ $cita->medico_id ?? '' }}");
        $('.bloqueable').prop('disabled', false);
        $('#medico_id_select').prop('disabled', false);
        
        // 4. Disparar especialidad
        // Usamos un pequeño delay para asegurar que el DOM cargó el atributo data-especialidad
        setTimeout(function() {
            $('#medico_id_select').trigger('change');
        }, 300);
    }

    // 3. LÓGICA DE BÚSQUEDA DE CÉDULA (Adaptada a los dos campos)
    $('#btn_consultar').on('click', function() {
        let cedula = $('#buscar_cedula').val();
        if (!$('#buscar_cedula').inputmask("isComplete")) {
            alert("Ingrese una cédula completa.");
            return;
        }

        $.get('/paciente/buscar-por-cedula/' + cedula)
            .done(function(data) {
                if (data.status === 'success') {
                    $('#seccion_registro_nuevo').slideUp();
                    $('#seccion_paciente_existente').slideDown();
                    
                    // RELLENAR LOS DOS CAMPOS NUEVOS
                    $('#nombre_paciente_ex').val(data.nombre);
                    $('#apellido_paciente_ex').val(data.apellido);
                    
                    $('#paciente_id_hidden').val(data.id); 
                    $('.bloqueable').prop('disabled', false);
                    $('#medico_id_select').prop('disabled', false);
                } else {
                    if (confirm("El paciente no existe. ¿Desea registrarlo?")) {
                        $('#seccion_paciente_existente').slideUp();
                        $('#seccion_registro_nuevo').slideDown();
                        $('#paciente_id_hidden').val(''); 
                        $('#cedula_buscada').val(cedula);
                        $('.bloqueable').prop('disabled', true);
                    }
                }
            });
    });

    // 4. LÓGICA DE ESPECIALIDAD Y BOTÓN DE REGISTRO RÁPIDO
    $('#medico_id_select').on('change', function() {
        let especialidad = $(this).find(':selected').data('especialidad');
        $('#especialidad_display').val(especialidad ? especialidad : '');
    });

    $('#btn_guardar_paciente_rapido').on('click', function() {
        let nombre = $('#nombre_paciente').val();
        let celular = $('#celular_paciente').val();

        if (nombre == "" || celular.length !== 8) {
            alert("Nombre y Celular (8 dígitos) son obligatorios.");
            return;
        }
        $('.bloqueable').prop('disabled', false);
        $('#medico_id_select').prop('disabled', false);
        $('#seccion_registro_nuevo input').prop('readonly', true);
        $(this).html('<i class="fas fa-check"></i> Validado').addClass('btn-success').prop('disabled', true);
    });

    // 5. HABILITAR ANTES DE ENVIAR (Indispensable para Update)
    $('form').on('submit', function() {
        $('.bloqueable').prop('disabled', false);
        $('#medico_id_select').prop('disabled', false);
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

</style>
@endpush


