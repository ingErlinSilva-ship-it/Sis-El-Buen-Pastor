@extends('adminlte::page')

@section('title')
    {{ config('adminlte.title') }}
@stop

@section('content_header')
@stop

@section('content')
    <section class="content container-fluid pt-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                {{-- Formulario de creación --}}
                <form method="POST" action="{{ route('enfermedade.store') }}" role="form" enctype="multipart/form-data">
                    @csrf
                    {{-- Incluimos el form que ya tiene la lógica de diseño --}}
                    @include('enfermedade.form')
                </form>
            </div>
        </div>
    </section>
@stop

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        {{-- Alerta de éxito unificada --}}
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: '¡Operación Exitosa!',
                text: '{{ session("success") }}',
                showConfirmButton: false,
                timer: 2500,
                timerProgressBar: true,
                customClass: { popup: 'rounded-lg' }
            });
        @endif
    });
</script>
@endpush

@push('css')
<style type="text/css">
    /* Aseguramos que no haya sombras dobles y el espaciado sea correcto */
    .content-wrapper {
        background-color: #f4f6f9 !important;
    }
    
    /* Personalización del SweetAlert para que combine con el azul de creación */
    .rounded-lg {
        border-radius: 15px !important;
    }
</style>
@endpush