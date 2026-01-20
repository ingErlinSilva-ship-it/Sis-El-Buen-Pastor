@extends('adminlte::page')

@section('title')
    {{ config('adminlte.title') }}
    @hasSection('subtitle') | @yield('subtitle') @endif
@stop

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

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Update') }} Cita</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('cita.update', $cita->id) }}" role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf
                            @include('cita.form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="modalSeleccionPaciente" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-users mr-2"></i>Pacientes Encontrados</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Seleccione al paciente que asistirá a la consulta:</p>
                    <div id="lista-pacientes" class="list-group">
                        </div>
                </div>
            </div>
        </div>
    </div>
@stop

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

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.8/jquery.inputmask.min.js"></script>
<script>
$(document).ready(function() {
    // 1. CONFIGURACIÓN INICIAL (Máscaras)
    $('#buscar_cedula').inputmask("999-999999-9999A");
    $('#celular_paciente').inputmask("99999999", {placeholder: "", clearMaskOnLostFocus: true});

    // 2. LÓGICA DE AUTO-CARGA (Para que el Edit inicie con los datos actuales)
    let pacienteIdExistente = $('#paciente_id_hidden').val();

    if (pacienteIdExistente) {
        $('#buscar_cedula').val("{{ $cita->paciente->cedula ?? $cita->paciente->tutor_cedula }}");
        $('#seccion_paciente_existente').show();
        $('#nombre_paciente_ex').val("{{ $cita->paciente->usuario->nombre ?? '' }}");
        $('#apellido_paciente_ex').val("{{ $cita->paciente->usuario->apellido ?? '' }}");
        
        $('#medico_id_select').val("{{ $cita->medico_id ?? '' }}");
        $('.bloqueable').prop('disabled', false);
        $('#medico_id_select').prop('disabled', false);
        
        setTimeout(function() {
            $('#medico_id_select').trigger('change');
        }, 300);
    }

    // 3. LÓGICA DE BÚSQUEDA DE CÉDULA (Igual que en Create)
    $('#btn_consultar').on('click', function() {
        let cedula = $('#buscar_cedula').val();
        if (!$('#buscar_cedula').inputmask("isComplete")) {
            alert("Ingrese una cédula completa.");
            return;
        }

        $.get('/paciente/buscar-por-cedula/' + cedula)
            .done(function(response) {
                if (response.status === 'success') {
                    if (response.count > 1) {
                        $('#lista-pacientes').empty();
                        response.data.forEach(p => {
                            $('#lista-pacientes').append(`
                                <button type="button" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" 
                                    onclick="seleccionarPacienteManual(${p.id}, '${p.nombre}', '${p.apellido}')">
                                    <span><i class="fas fa-user mr-2"></i> ${p.nombre} ${p.apellido}</span>
                                    <span class="badge badge-info badge-pill">${p.tipo}</span>
                                </button>
                            `);
                        });
                        $('#modalSeleccionPaciente').modal('show');
                    } else {
                        const p = response.data[0];
                        cargarDatosPaciente(p.id, p.nombre, p.apellido);
                    }
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

    // Funciones globales para el Modal
    window.seleccionarPacienteManual = function(id, nombre, apellido) {
        $('#modalSeleccionPaciente').modal('hide');
        cargarDatosPaciente(id, nombre, apellido);
    };

    function cargarDatosPaciente(id, nombre, apellido) {
        $('#seccion_registro_nuevo').slideUp();
        $('#seccion_paciente_existente').slideDown();
        $('#nombre_paciente_ex').val(nombre);
        $('#apellido_paciente_ex').val(apellido);
        $('#paciente_id_hidden').val(id);
        $('.bloqueable').prop('disabled', false);
        $('#medico_id_select').prop('disabled', false);
    }

    // 4. LÓGICA DE ESPECIALIDAD
    $('#medico_id_select').on('change', function() {
        let especialidad = $(this).find(':selected').data('especialidad');
        $('#especialidad_display').val(especialidad ? especialidad : '');
    });

    // 5. REGISTRO RÁPIDO EN EDIT
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

    // Validación de dias y horas
    // A. Validación inmediata de la FECHA (Detectar Sábados)
    $('#fecha').on('change', function() {
        let fechaSeleccionada = new Date($(this).val() + 'T00:00:00'); // Forzamos hora local
        let dia = fechaSeleccionada.getUTCDay(); // 0:Dom, 1:Lun... 6:Sáb

        if (dia === 6) { // Sábado
            alert("La clínica permanece cerrada los días sábados. Por favor, seleccione otro día.");
            $(this).val(''); // Limpiamos la fecha
            return;
        }
    });

    // B. Validación de la HORA (Ajuste de límites exactos)
    $('#hora').on('change', function() {
        let fechaVal = $('#fecha').val();
        if (!fechaVal) {
            alert("Primero debe seleccionar una fecha.");
            $(this).val('');
            return;
        }

    let fecha = new Date(fechaVal + 'T00:00:00');
    let hora = $(this).val();
    let dia = fecha.getUTCDay();

        if (dia === 0) { 
            if(hora < "08:00" || hora > "11:30") {
                alert("Los Domingos la atención es solo por la mañana (08:00 - 12:00).");
                $(this).val('');
            }
        } else if (dia >= 1 && dia <= 5) { // Lunes a Viernes
        // 01:30 PM (13:30) a 06:00 PM (18:00)
        if (hora < "13:29" || hora > "17:30") {
            alert("De lunes a viernes la atención es de 01:30 PM a 06:00 PM.");
            $(this).val('');
        }
    }
    });

     // Validación disponibiliadad del medico
    $('#fecha, #hora, #medico_id_select').on('change', function() {
        let fecha = $('#fecha').val();
        let hora = $('#hora').val();
        let medico_id = $('#medico_id_select').val();

        // Solo validamos si los tres campos tienen valor
        $.get('/citas/verificar-disponibilidad', {
            fecha: fecha,
            hora: hora,
            medico_id: medico_id,
            cita_id: "{{ $cita->id }}" // Enviamos el ID para que no se bloquee a sí misma
        })
    });

    // 6. HABILITAR ANTES DE ENVIAR
    $('form').on('submit', function() {
        $('.bloqueable').prop('disabled', false);
        $('#medico_id_select').prop('disabled', false);
    });
});
</script>
@endpush

@push('css')
<style type="text/css">
    .list-group-item-action { cursor: pointer; }
    .list-group-item-action:hover { background-color: #f8f9fa; }
</style>
@endpush


