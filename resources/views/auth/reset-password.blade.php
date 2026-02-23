<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña | El Buen Pastor</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body, html { height: 100%; margin: 0; }
        .split-screen { display: flex; height: 100vh; width: 100%; }
        .image-side {
            flex: 1;
            background: linear-gradient(rgba(0, 0, 0, 0.45), rgba(0, 0, 0, 0.45)), 
                        url('https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            display: none;
        }
        @media (min-width: 992px) {
            .image-side {
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 40px;
            }
        }
        .form-side {
            width: 100%;
            max-width: 500px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background: white;
            position: relative;
        }
        @media (min-width: 992px) {
            .form-side { width: 450px; }
        }
    </style>
</head>
<body class="bg-gray-100">

<div class="split-screen">

    <!-- LADO IMAGEN -->
    <div class="image-side">
        <div class="text-white px-12">
            <h1 class="text-6xl font-extrabold mb-4 uppercase tracking-tight leading-none">Consultorio Médico</h1>
            <h1 class="text-6xl font-extrabold mb-4 uppercase tracking-tight leading-none">El Buen Pastor</h1>
            <p class="text-2xl italic font-light opacity-90 border-l-4 border-white pl-4">
                "Nuestro compromiso está con la salud de los pacientes"
            </p>
        </div>
    </div>

    <!-- LADO FORMULARIO -->
    <div class="form-side">

        <!-- BOTÓN VOLVER -->
        <a href="{{ route('login') }}" 
           class="absolute top-8 left-8 text-gray-400 hover:text-blue-600 transition-colors duration-300 flex items-center gap-2 font-medium">
            <i class="fas fa-chevron-left"></i>
            <span>Volver</span>
        </a>

        <div class="w-full">
            <div class="text-center mb-10">
                <img src="{{ asset('assets/img/bg/logocotCL.jpg') }}" 
                     class="mx-auto mb-4 shadow-sm rounded-lg"
                     style="max-height: 100px; width: auto;">
                <h2 class="text-3xl font-extrabold text-gray-800 tracking-tight">
                    RESTABLECER CONTRASEÑA
                </h2>
                <p class="text-gray-500 mt-2">
                    Ingresa tu nueva contraseña
                </p>
            </div>

            <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
                @csrf

                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">
                        Correo Electrónico
                    </label>
                    <input type="email"
                           name="email"
                           value="{{ old('email', $request->email) }}"
                           required
                           class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition bg-gray-50">
                    @error('email')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nueva contraseña -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">
                        Nueva Contraseña
                    </label>
                    <input type="password"
                           name="password"
                           required
                           class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition bg-gray-50">
                    @error('password')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirmar contraseña -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">
                        Confirmar Contraseña
                    </label>
                    <input type="password"
                           name="password_confirmation"
                           required
                           class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition bg-gray-50">
                </div>

                <div class="pt-2">
                    <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-lg shadow-lg hover:shadow-blue-200 transition-all duration-300 uppercase tracking-wide">
                        Restablecer Contraseña
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>
</body>
</html>
