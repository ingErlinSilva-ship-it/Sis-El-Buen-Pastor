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
    <div class="container-fluid">
        <form method="POST" action="{{ route('cita.store') }}" role="form" enctype="multipart/form-data">
            @csrf          
            
            @include('cita.form')
        </form>
    </div>

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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // 1. CONFIGURACIÓN DE MÁSCARAS
    $('#buscar_cedula').inputmask("999-999999-9999A");
    $('#celular_paciente').inputmask("99999999", {placeholder: "", clearMaskOnLostFocus: true});

    // Función global para Alertas Estilizadas
    const toast = (icon, title, text) => {
        Swal.fire({
            icon: icon,
            title: title,
            text: text,
            confirmButtonColor: '#007bff',
            borderRadius: '15px'
        });
    };

// 2. LÓGICA DEL BUSCADOR DE CÉDULA
$('#btn_consultar').on('click', function() {
    let $btn = $(this); // <--- DEFINIMOS LA VARIABLE AQUÍ
    let cedula = $('#buscar_cedula').val();
    
    if (!$('#buscar_cedula').inputmask("isComplete")) {
        toast('error', 'Cédula Incompleta', 'Por favor, ingrese un número de cédula válido.');
        return;
    }

    // EFECTO DE CARGA: Deshabilitamos y ponemos el spinner
    $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Buscando...');

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
                    Swal.fire({
                        icon: 'success',
                        title: 'Paciente Encontrado',
                        text: `Paciente ${p.nombre} listo para agendar.`,
                        timer: 3000,
                        showConfirmButton: false
                    });
                }
            } else {
                Swal.fire({
                    title: 'Paciente no registrado',
                    text: `La cédula ${cedula} no existe. ¿Desea realizar un registro rápido?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#007bff',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Sí, registrar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#seccion_paciente_existente').slideUp();
                        $('#seccion_registro_nuevo').slideDown();
                        $('#paciente_id_hidden').val(''); 
                        $('#cedula_buscada').val(cedula);
                        $('#nombre_paciente').focus();
                        $('.bloqueable').prop('disabled', true);
                    }
                });
            }
        })
        .fail(function() {
            toast('error', 'Error de Red', 'No se pudo conectar con el servidor.');
        })
        .always(function() {
            // QUITAMOS EFECTO: Restauramos el botón al terminar (éxito o error)
            $btn.prop('disabled', false).html('<i class="fa fa-search"></i> Buscar');
        });
});

    // 3. VALIDACIÓN DE FECHA (SÁBADOS)
    $('#fecha').on('change', function() {
        let fechaSeleccionada = new Date($(this).val() + 'T00:00:00');
        let dia = fechaSeleccionada.getUTCDay(); 

        if (dia === 6) { // Sábado
            toast('warning', 'Clínica Cerrada', 'Los días sábados no hay atención médica. Por favor, seleccione otro día.');
            $(this).val('');
            return;
        }
    });

    // 4. VALIDACIÓN DE HORA
    $('#hora').on('change', function() {
        let fechaVal = $('#fecha').val();
        if (!fechaVal) {
            toast('info', 'Seleccione Fecha', 'Primero debe seleccionar una fecha para validar el horario.');
            $(this).val('');
            return;
        }

        let fecha = new Date(fechaVal + 'T00:00:00');
        let hora = $(this).val();
        let dia = fecha.getUTCDay();

        if (dia === 0) { // Domingo
            if(hora < "08:00" || hora > "11:30") {
                toast('warning', 'Horario de Domingo', 'Los Domingos la atención es solo por la mañana (08:00 AM - 12:00 PM).');
                $(this).val('');
            }
        } else if (hora < "13:30" || hora > "17:30"){ // Lunes a Viernes
            toast('warning', 'Horario Vespertino', 'De lunes a viernes la atención es únicamente por la tarde (01:30 PM - 05:30 PM).');
            $(this).val('');
        }
    });

    // 5. DISPONIBILIDAD DEL MÉDICO
    $('#fecha, #hora, #medico_id_select').on('change', function() {
        let fecha = $('#fecha').val();
        let hora = $('#hora').val();
        let medico_id = $('#medico_id_select').val();

        if (fecha && hora && medico_id) {
            $.get('/citas/verificar-disponibilidad', { fecha, hora, medico_id })
                .done(function(response) {
                    if (!response.disponible) {
                        toast('error', 'Médico Ocupado', 'El médico seleccionado ya tiene una cita a esa hora. Por favor, elija otro horario.');
                        $('#hora').val('');
                    }
                });
        }
    });

    // 6. VALIDACIÓN NUEVO PACIENTE
    $('#btn_guardar_paciente_rapido').on('click', function() {
        let nombre = $('#nombre_paciente').val();
        let apellido = $('#apellido_paciente').val();
        let celular = $('#celular_paciente').val();

        if (nombre == "" || apellido == "" || celular.length !== 8) {
            toast('error', 'Campos Incompletos', 'Nombre, Apellido y un Celular válido (8 dígitos) son obligatorios.');
            return;
        }

        $('.bloqueable').prop('disabled', false);
        $('#medico_id_select').prop('disabled', false);
        $('#seccion_registro_nuevo input').prop('readonly', true);
        
        $(this).html('<i class="fas fa-check"></i> Paciente Validado')
               .removeClass('btn-primary').addClass('btn-success').prop('disabled', true);
        
        toast('success', 'Validación Exitosa', 'Paciente registrado temporalmente. Puede continuar con los datos de la cita.');
    });

    // Funciones auxiliares
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

    $('#medico_id_select').on('change', function() {
        let especialidad = $(this).find(':selected').data('especialidad');
        $('#especialidad_display').val(especialidad ? especialidad : '');
    });

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


