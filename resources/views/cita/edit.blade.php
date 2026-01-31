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
        <form method="POST" action="{{ route('cita.update', $cita->id) }}" role="form" enctype="multipart/form-data">
            {{ method_field('PATCH') }}
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
                    <p>Seleccione al paciente que asistirá a la consulta:</p>
                    <div id="lista-pacientes" class="list-group">
                        </div>
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
    // 1. CONFIGURACIÓN INICIAL (Máscaras)
    $('#buscar_cedula').inputmask("999-999999-9999A");
    $('#celular_paciente').inputmask("99999999", {placeholder: "", clearMaskOnLostFocus: true});

    // Función global para Alertas Estilizadas
    const toast = (icon, title, text) => {
        Swal.fire({
            icon: icon,
            title: title,
            text: text,
            confirmButtonColor: '#28a745', // Color verde para edición
            borderRadius: '15px'
        });
    };

    // 2. LÓGICA DE AUTO-CARGA (Para Edit)
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

    // 3. LÓGICA DE BÚSQUEDA DE CÉDULA
    $('#btn_consultar').on('click', function() {
        let cedula = $('#buscar_cedula').val();
        if (!$('#buscar_cedula').inputmask("isComplete")) {
            toast('error', 'Dato Incompleto', 'Ingrese una cédula completa.');
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
                    Swal.fire({
                        title: 'Paciente no encontrado',
                        text: "El paciente no existe. ¿Desea realizar un registro rápido?",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Sí, registrar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#seccion_paciente_existente').slideUp();
                            $('#seccion_registro_nuevo').slideDown();
                            $('#paciente_id_hidden').val(''); 
                            $('#cedula_buscada').val(cedula);
                            $('.bloqueable').prop('disabled', true);
                        }
                    });
                }
            });
    });

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
            toast('error', 'Campos requeridos', 'Nombre y Celular (8 dígitos) son obligatorios.');
            return;
        }
        $('.bloqueable').prop('disabled', false);
        $('#medico_id_select').prop('disabled', false);
        $('#seccion_registro_nuevo input').prop('readonly', true);
        $(this).html('<i class="fas fa-check"></i> Validado').addClass('btn-success').prop('disabled', true);
        toast('success', 'Validado', 'Datos del paciente listos.');
    });

    // 6. VALIDACIONES DE FECHA Y HORA
    $('#fecha').on('blur', function() { // Usamos 'blur' para que valide al salir del campo
        let valor = $(this).val();
        
        // Si la fecha no tiene 10 caracteres (YYYY-MM-DD), no validamos aún
        if (valor.length < 10) return;

        let fechaSeleccionada = new Date(valor + 'T00:00:00');
        let anio = fechaSeleccionada.getFullYear();

        // Evitamos años absurdos como el año 0002 que mencionabas
        if (anio < 1900 || anio > 2100) return;

        let dia = fechaSeleccionada.getUTCDay(); 

        if (dia === 6) { // Sábado
            toast('warning', 'Clínica Cerrada', 'La clínica permanece cerrada los días sábados. Por favor, seleccione otro día.');
            $(this).val('');
            return;
        }
    });
    $('#hora').on('change', function() {
        let fechaVal = $('#fecha').val();
        if (!fechaVal) {
            toast('info', 'Fecha Requerida', 'Primero debe seleccionar una fecha.');
            $(this).val('');
            return;
        }

        let fecha = new Date(fechaVal + 'T00:00:00');
        let hora = $(this).val();
        let dia = fecha.getUTCDay();

        if (dia === 0) { // Domingo
            if(hora < "08:00" || hora > "11:30") {
                toast('warning', 'Horario Domingo', 'Los Domingos la atención es solo por la mañana (08:00 AM - 12:00 PM).');
                $(this).val('');
            }
        } else if (dia >= 1 && dia <= 5) { // Lunes a Viernes
            if (hora < "13:29" || hora > "17:30") {
                toast('warning', 'Horario Vespertino', 'De lunes a viernes la atención es de 01:30 PM a 06:00 PM.');
                $(this).val('');
            }
        }
    });

    // 7. DISPONIBILIDAD DEL MÉDICO
    $('#fecha, #hora, #medico_id_select').on('change', function() {
        let fecha = $('#fecha').val();
        let hora = $('#hora').val();
        let medico_id = $('#medico_id_select').val();

        if (fecha && hora && medico_id) {
            $.get('/citas/verificar-disponibilidad', {
                fecha, hora, medico_id,
                cita_id: "{{ $cita->id }}" // Evita auto-bloqueo en Edit
            }).done(function(response) {
                if (!response.disponible) {
                    toast('error', 'Cita Duplicada', 'El médico seleccionado ya tiene una cita a esa hora. Elija otro horario.');
                    $('#hora').val('');
                }
            });
        }
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
    .list-group-item-action { cursor: pointer; }
    .list-group-item-action:hover { background-color: #f8f9fa; }
</style>
@endpush


