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
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
    let cropper;
    const fotoInput = document.getElementById('foto');
    const imageToCrop = document.getElementById('imageToCrop');
    const preview = document.getElementById('preview');

    fotoInput.addEventListener('change', function (e) {
        const files = e.target.files;
        if (files && files.length > 0) {
            const reader = new FileReader();
            reader.onload = function (event) {
                imageToCrop.src = event.target.result;
                $('#modalCrop').modal('show');
            };
            reader.readAsDataURL(files[0]);
        }
    });

    $('#modalCrop').on('shown.bs.modal', function () {
        cropper = new Cropper(imageToCrop, {
            aspectRatio: 1, // Mantiene el círculo perfecto
            viewMode: 1,
            autoCropArea: 1,
        });
    }).on('hidden.bs.modal', function () {
        cropper.destroy();
    });

    document.getElementById('saveCrop').addEventListener('click', function () {
        const canvas = cropper.getCroppedCanvas({ width: 400, height: 400 });
        canvas.toBlob(function (blob) {
            const url = URL.createObjectURL(blob);
            preview.src = url; // Actualiza la miniatura que tú ya tienes

            // Reemplaza el archivo en el input para que el controlador lo reciba
            const file = new File([blob], "perfil.jpg", { type: "image/jpeg" });
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            fotoInput.files = dataTransfer.files;

            $('#modalCrop').modal('hide');
        }, 'image/jpeg');
    });
</script>
@endpush

@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
<style>
    .img-container { min-height: 300px; max-height: 500px; width: 100%; }
</style>
@endpush