@extends('adminlte::page')

@section('title')
{{ config('adminlte.title') }}
@stop

@section('content')
<section class="content container-fluid pt-4">
    <div class="row justify-content-center">
        <div class="col-md-12 edit-mode">
            <form method="POST" action="{{ route('alergia.update', $alergia->id) }}" role="form"
                enctype="multipart/form-data">
                {{ method_field('PATCH') }}
                @csrf
                @include('alergia.form')
            </form>
        </div>
    </div>
</section>
@stop

@push('js')
    <script>
        $(document).ready(function () {
            // Ajuste de textos para el modo edición
            $('.edit-mode .card-title').text('{{ __("Actualizar Registro de Alergia") }}');

            // Forzamos el botón a Verde (Success)
            var btnGuardar = $('.edit-mode button[type="submit"]');
            btnGuardar.addClass('btn-success-invert').removeClass('btn-primary-invert');
            btnGuardar.html('<i class="fas fa-sync-alt mr-2"></i> {{ __("Actualizar Registro") }}');
        });
    </script>
@endpush

@push('css')
    <style type="text/css">
        /* FORZAR IDENTIDAD VERDE (EDITAR) */
        .edit-mode .card-header {
            border-top: 4px solid #28a745 !important;
        }

        .edit-mode .rounded-circle {
            background-color: #e8f5e9 !important;
        }

        .edit-mode .fa-allergies,
        .edit-mode .text-primary,
        .edit-mode .label-custom, {
            color: #28a745 !important;
        }

        /* NOMENCLATURA BOTÓN VERDE (Inversión) */
        .btn-success-invert.btn-success-invert {
            background-color: #ffffff !important;
            border: 2px solid #28a745 !important;
            color: #28a745 !important;
            border-radius: 8px !important;
            font-weight: 600 !important;
            transition: all 0.3s ease-in-out !important;
            display: inline-flex;
            align-items: center;
        }

        .btn-success-invert.btn-success-invert:hover {
            background-color: #28a745 !important;
            color: #ffffff !important;
            border-color: #28a745 !important;
        }

        .btn-success-invert.btn-success-invert:hover i {
            color: #ffffff !important;
        }

        .edit-mode .form-control:focus {
            border-color: #28a745 !important;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.2) !important;
        }
    </style>
@endpush