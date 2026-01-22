@extends('adminlte::page')

@section('title')
    {{ config('adminlte.title') }}
    @hasSection('subtitle') | @yield('subtitle') @endif
@stop

{{-- 1. Limpiamos el encabezado para evitar textos duplicados --}}
@section('content_header')
@stop

@section('content')
    <div class="container-fluid">
        <form method="POST" action="{{ route('usuario.store') }}" role="form" enctype="multipart/form-data">
            @csrf

            {{-- Aquí se incluye el diseño del formulario --}}
            @include('usuario.form')

        </form>
    </div>
@stop

@section('footer')
    <div class="float-right d-none d-sm-block text-muted">Version: {{ config('app.version', '1.0.0') }}</div>
    <strong>© 2025 - Consultorio El Buen Pastor. Desarrollado por Levi Ruiz y Erlin Silva.</strong>
@stop

@push('js')
<script>
    $(document).ready(function() {
        // Lógica para previsualización de foto o validaciones aquí
    });
</script>
@endpush

@push('css')
<style type="text/css">
    /* Estilos específicos para la vista de usuarios */
</style>
@endpush