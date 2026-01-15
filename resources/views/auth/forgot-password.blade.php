<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña | El Buen Pastor</title>
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
        @media (min-width: 992px) { .image-side { display: flex; align-items: center; justify-content: flex-start; padding: 40px; } }
        .form-side { width: 100%; max-width: 500px; display: flex; align-items: center; justify-content: center; padding: 40px; background: white; position: relative; }
        @media (min-width: 992px) { .form-side { width: 450px; } }
    </style>
</head>
<body class="bg-gray-100">

    <div class="split-screen">
        <div class="image-side">
            <div class="text-white px-12">
                <h1 class="text-6xl font-extrabold mb-4 uppercase tracking-tight leading-none">Consultorio Médico</h1>
                <h1 class="text-6xl font-extrabold mb-4 uppercase tracking-tight leading-none">El Buen Pastor</h1>
                <p class="text-2xl italic font-light opacity-90 border-l-4 border-white pl-4">"Nuestro compromiso está con la salud de los pacientes"</p>
            </div>
        </div>

        <div class="form-side">
            {{-- Botón para volver al Login --}}
            <a href="{{ route('login') }}" class="absolute top-8 left-8 text-gray-400 hover:text-blue-600 transition-colors duration-300 flex items-center gap-2 font-medium">
                <i class="fas fa-chevron-left"></i>
                <span>Volver al Login</span>
            </a>

            <div class="w-full">
                <div class="text-center mb-8">
                    <div class="bg-blue-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <img src="{{ asset('assets/img/bg/logocotCL.jpg') }}" alt="logocot" class="mx-auto mb-4 shadow-sm rounded-lg" style="max-height: 100px; width: auto;">
                    </div>
                    <h2 class="text-3xl font-extrabold text-gray-800 tracking-tight uppercase">¿Olvidó su clave?</h2>
                    <p class="text-gray-500 mt-4 text-sm px-4">
                        No hay problema. Indíquenos su dirección de correo electrónico y le enviaremos un enlace para restablecer su contraseña.
                    </p>
                </div>

                <x-auth-session-status class="mb-4 bg-green-100 p-3 rounded-lg text-green-700 text-sm font-bold text-center" :status="session('status')" />

                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-bold text-gray-700 mb-1">Correo Electrónico Registrado</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input id="email" type="email" name="email" :value="old('email')" required autofocus 
                                class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition bg-gray-50 shadow-sm"
                                placeholder="usuario@clinica.com">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600 text-xs" />
                    </div>

                    <div>
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-lg shadow-lg hover:shadow-blue-200 transition-all duration-300 uppercase tracking-wide">
                            Enviar enlace de recuperación
                        </button>
                    </div>
                </form>

                <div class="text-center mt-10">
                    <p class="text-xs text-gray-400">&copy; 2025 Clínica El Buen Pastor - Soporte Técnico</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>