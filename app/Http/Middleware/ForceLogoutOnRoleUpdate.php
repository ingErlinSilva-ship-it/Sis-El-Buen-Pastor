<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class ForceLogoutOnRoleUpdate
{
 public function handle(Request $request, Closure $next)
{
    if (Auth::check()) {
        $user = Auth::user();
        
        // REVISIÓN EXTRA: Si por alguna razón no hay caché pero el estado en BD ya es 0
        if ($user->estado == 0) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->with('error_toast', 'Su cuenta ha sido desactivada.');
        }

        $motivo = Cache::get('force_logout_user_' . $user->id);

        if ($motivo) {
            Cache::forget('force_logout_user_' . $user->id);
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $mensaje = ($motivo === 'desactivado') 
                ? 'Su cuenta ha sido desactivada. Contacte al administrador.' 
                : 'Sus permisos han cambiado. Inicie sesión de nuevo.';

            return redirect()->route('login')->with('error_toast', $mensaje);
        }
    }
    return $next($request);
}
}