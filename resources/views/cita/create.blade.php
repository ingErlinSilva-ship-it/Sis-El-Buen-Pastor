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
                        <span class="card-title">{{ __('Create') }} Citas</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('cita.store') }}" role="form" enctype="multipart/form-data">
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
                    <p>Se encontraron varios pacientes asociados a esta cédula. Por favor seleccione uno:</p>
                    <div id="lista-pacientes" class="list-group">
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
@stop

{{-- Create a common footer --}}

@section('footer')
    <div class="float-right">Version: {{ config('app.version', '1.0.0') }}</div>
    <strong>© 2025 - Consultorio El Buen Pastor. Desarrollado por Levi Ruiz y Erlin Silva.</strong>
@stop

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
            .done(function(response) {
                if (response.status === 'success') {
                    if (response.count > 1) {
                        // CASO: MÚLTIPLES PACIENTES (Modal)
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
                        // CASO: UN SOLO PACIENTE
                        const p = response.data[0];
                        cargarDatosPaciente(p.id, p.nombre, p.apellido);
                        alert("Paciente " + p.nombre + " listo para agendar.");
                    }
                } else {
                    // CASO: PACIENTE NUEVO
                    if (confirm("El paciente con cédula " + cedula + " no existe. ¿Desea realizar un registro rápido?")) {
                        $('#seccion_paciente_existente').slideUp();
                        $('#seccion_registro_nuevo').slideDown();
                        $('#paciente_id_hidden').val(''); 
                        $('#cedula_buscada').val(cedula);
                        $('#nombre_paciente').focus();
                        $('.bloqueable').prop('disabled', true);
                    }
                }
            });
    });

    // Funciones auxiliares para la carga de datos
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

        $('.bloqueable').prop('disabled', false);
        $('#medico_id_select').prop('disabled', false);
        
        $('#seccion_registro_nuevo input').prop('readonly', true);
        $(this).html('<i class="fas fa-check"></i> Paciente Validado')
               .removeClass('btn-primary')
               .addClass('btn-success')
               .prop('disabled', true);
        
        alert("Paciente validado correctamente. Proceda a llenar los datos de la cita.");
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
        } else if (hora < "13:30" || hora > "17:30"){ // Lunes a Viernes
                alert("De lunes a viernes solo se atiende por la tarde (a partir de las 1:30 PM).");
                $(this).val('');
        }
    });

    // Validación disponibiliadad del medico
    $('#fecha, #hora, #medico_id_select').on('change', function() {
        let fecha = $('#fecha').val();
        let hora = $('#hora').val();
        let medico_id = $('#medico_id_select').val();

        // Solo validamos si los tres campos tienen valor
        if (fecha && hora && medico_id) {
            $.get('/citas/verificar-disponibilidad', {
                fecha: fecha,
                hora: hora,
                medico_id: medico_id
            }).done(function(response) {
                if (!response.disponible) {
                    alert("¡Atención! El médico seleccionado ya tiene una cita a esa hora. Por favor, elija otro horario.");
                    $('#hora').val(''); // Limpiamos la hora para obligar a cambiarla
                }
            });
        }
    });

    // 5. PREVENCIÓN DE CAMPOS VACÍOS AL ENVIAR
    $('form').on('submit', function() {
        $('.bloqueable').prop('disabled', false);
        $('#medico_id_select').prop('disabled', false);
    });
});
</script>
@endpush

@push('css')
<style type="text/css">
    .navbar-search-block, 
    .form-inline .input-group {
        display: none !important;
    }
    /* Estilo para los items del modal */
    .list-group-item-action {
        cursor: pointer;
        transition: all 0.2s;
    }
    .list-group-item-action:hover {
        background-color: #f8f9fa;
        transform: scale(1.02);
    }
</style>
@endpush


