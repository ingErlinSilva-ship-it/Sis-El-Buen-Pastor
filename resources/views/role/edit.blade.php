@extends('adminlte::page')

@section('title')
{{ config('adminlte.title') }}
@stop

@section('content_header')
@stop

@section('content')
    <div class="container-fluid">
        <form method="POST" action="{{ route('role.update', $role->id) }}" role="form" enctype="multipart/form-data">
            {{ method_field('PATCH') }}
            @csrf

            @include('role.form')

        </form>
    </div>
@stop


@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
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
</style>
@endpush