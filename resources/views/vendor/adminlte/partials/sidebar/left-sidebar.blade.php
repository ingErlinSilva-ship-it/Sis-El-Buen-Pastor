<aside class="main-sidebar {{ config('adminlte.classes_sidebar', 'sidebar-dark-primary elevation-4') }}">

    {{-- Sidebar brand logo --}}
    @if(config('adminlte.logo_img_xl'))
        @include('adminlte::partials.common.brand-logo-xl')
    @else
        @include('adminlte::partials.common.brand-logo-xs')
    @endif

    {{-- Sidebar menu --}}
    <div class="sidebar">
        <nav class="pt-2">
            <ul class="nav nav-pills nav-sidebar flex-column {{ config('adminlte.classes_sidebar_nav', '') }}"
                data-widget="treeview" role="menu"
                @if(config('adminlte.sidebar_nav_animation_speed') != 300)
                    data-animation-speed="{{ config('adminlte.sidebar_nav_animation_speed') }}"
                @endif
                @if(!config('adminlte.sidebar_nav_accordion'))
                    data-accordion="false"
                @endif>
                {{-- Configured sidebar links --}}
                @each('adminlte::partials.sidebar.menu-item', $adminlte->menu('sidebar'), 'item')
            </ul>
        </nav>

        {{-- Nombre del usuario en la parte inferior --}}
        {{-- Contenedor con ajuste para eliminar scroll --}}
        <div class="px-2 pb-4" style="position: absolute; bottom: 0; width: 100%; left: 0; right: 0; overflow: hidden !important;">
            
            {{-- Card de Usuario --}}
            <div class="mx-2 p-3 shadow-sm" style="background-color: #0b4c81; border-radius: 1rem;">
                <p class="text-uppercase font-weight-bold mb-1" style="font-size: 0.65rem; color: #94a3b8; letter-spacing: 0.05em;">
                    Usuario
                </p>
                <p class="font-weight-bold text-white text-truncate mb-1">
                   {{ Auth::user()->nombre }} {{ Auth::user()->apellido }}
                </p>
                <p class="mt-1 mb-0" style="font-size: 0.9rem; color: #38bdf8; text-transform: capitalize;">
                    @php
                        $rolNombre = strtolower(Auth::user()->role->first()->nombre ??'');
                    @endphp

                    @if($rolNombre == 'admin' || $rolNombre == 'administrador') 
                        Administrador
                    @elseif($rolNombre == 'doctor' || $rolNombre == 'medico') 
                        Doctor
                    @elseif($rolNombre == 'paciente') 
                        Paciente
                    @else 
                        {{ Auth::user()->role->first()->nombre ?? 'Sin Rol' }}
                    @endif
                </p>
            </div>

            {{-- Separador sutil --}}
            <div class="mx-4 my-2" style="border-top: 1px solid rgba(255,255,255,0.1);"></div>

            {{-- Botón de salida --}}
            <div class="px-2">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm btn-block d-flex align-items-center justify-content-center" 
                            style="background-color: #ef4444; border: none; border-radius: 0.5rem; height: 35px;">
                        <i class="fas fa-sign-out-alt mr-2"></i> Cerrar Sesión
                    </button>
                </form>
            </div>
        </div>
    </div>

</aside>
