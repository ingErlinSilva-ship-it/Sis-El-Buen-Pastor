<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | El Buen Pastor</title>
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
        @media (min-width: 992px) { .image-side { display: flex; align-items: center; justify-content: center; padding: 40px; } }
        /* Añadido position: relative para ubicar el botón Volver */
        .form-side { width: 100%; max-width: 500px; display: flex; align-items: center; justify-content: center; padding: 40px; background: white; position: relative; }
        @media (min-width: 992px) { .form-side { width: 450px; } }
    </style>
</head>
<body class="bg-gray-100">

    {{-- TU TOAST (Mantenido exactamente igual) --}}
    @if (session('error_toast'))
    <div id="toast-danger" class="fixed top-5 right-5 flex items-center max-w-xs py-2 px-3 rounded-lg shadow-lg z-50" style="background-color: #dc3545; color: white; width: fit-content;" role="alert">
        <div class="inline-flex items-center justify-center flex-shrink-0 w-6 h-6 rounded-lg bg-red-800 text-red-200 me-2">
            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM10 15a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm1-4a1 1 0 0 1-2 0V6a1 1 0 0 1 2 0v5Z"/>
            </svg>
        </div>
        <div class="ms-1 text-sm font-normal text-white">{{ session('error_toast') }}</div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toast = document.getElementById('toast-danger');
            if (toast) {
                setTimeout(() => {
                    toast.style.opacity = '0';
                    toast.style.transition = 'opacity 0.5s';
                }, 4500); 
                setTimeout(() => {
                    toast.style.display = 'none';
                }, 5000); 
            }
        });
    </script>
    @endif

    <div class="split-screen">
        <div class="image-side" style="display: flex; align-items: center; justify-content: flex-start; flex: 1;">
            <div class="text-white px-12">
                <h1 class="text-6xl font-extrabold mb-4 uppercase tracking-tight leading-none">Consultorio Médico</h1>
                <h1 class="text-6xl font-extrabold mb-4 uppercase tracking-tight leading-none">El Buen Pastor</h1>
                <p class="text-2xl italic font-light opacity-90 border-l-4 border-white pl-4">"Nuestro compromiso está con la salud de los pacientes"</p>
            </div>
        </div>

        <div class="form-side">
            
            {{-- BOTÓN RETROCEDER (Volver al Inicio) --}}
            <a href="/" class="absolute top-8 left-8 text-gray-400 hover:text-blue-600 transition-colors duration-300 flex items-center gap-2 font-medium">
                <i class="fas fa-chevron-left"></i>
                <span>Volver</span>
            </a>

            <div class="w-full">
                <div class=" text-center mb-10">
                    <img src="{{ asset('assets/img/bg/logocotCL.jpg') }}" alt="logocot" class="mx-auto mb-4 shadow-sm rounded-lg" style="max-height: 100px; width: auto;">
                    <h2 class="text-3xl font-extrabold text-gray-800 tracking-tight">INICIAR SESIÓN</h2>
                    <p class="text-gray-500 mt-2">Ingrese sus credenciales para acceder</p>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Correo Electrónico</label>
                        <input type="email" name="email" required class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition bg-gray-50" placeholder="nombre@clinica.com">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Contraseña</label>
                        <input type="password" name="password" required class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition bg-gray-50" placeholder="••••••••">
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center text-sm text-gray-600 cursor-pointer">
                            <input type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                            <span class="ms-2">Recordarme</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a class="text-sm font-bold text-blue-600 hover:text-blue-800 transition-colors" href="{{ route('password.request') }}">
                                ¿Olvidó su contraseña?
                            </a>
                        @endif
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-lg shadow-lg hover:shadow-blue-200 transition-all duration-300 uppercase tracking-wide">
                            Ingresar al Sistema
                        </button>
                        
                        {{-- ENLACE PARA REGISTRARSE --}}
                        <p class="text-center mt-6 text-gray-600 text-sm">
                            ¿No tienes una cuenta? 
                            <a href="{{ route('register') }}" class="text-blue-600 font-bold hover:text-blue-800 hover:underline transition-all">
                                Regístrate aquí
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>