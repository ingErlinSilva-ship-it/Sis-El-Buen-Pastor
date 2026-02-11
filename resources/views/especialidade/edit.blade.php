@extends('adminlte::page')

@section('title')
{{ config('adminlte.title') }}
@stop

@section('content_header')
@stop

@section('content')
    <div class="container-fluid pt-4">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <form method="POST" action="{{ route('especialidade.update', $especialidade->id) }}" role="form" enctype="multipart/form-data">
                    {{ method_field('PATCH') }}
                    @csrf
                    @include('especialidade.form')
                </form>
            </div>
        </div>
    </div>
@stop

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        {{-- Unificamos el título a "Operación Exitosa" para que coincida con Roles y Create --}}
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: '¡Actualizado!',
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
    .card {
        border-radius: 15px;
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
    }
</style>
@endpush