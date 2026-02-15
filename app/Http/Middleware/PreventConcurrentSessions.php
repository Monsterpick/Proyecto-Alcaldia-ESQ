<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PreventConcurrentSessions
{
    /**
     * Para Alcalde, Analista, Operador: actualizar session_last_activity.
     * Super Admin está exento (puede tener múltiples sesiones).
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();
        if (!$user->requiereSesionUnica()) {
            return $next($request);
        }

        $currentSessionId = $request->session()->getId();

        // Si la sesión activa registrada no coincide con la actual, cerrar (otro dispositivo tomó control)
        if ($user->active_session_id && $user->active_session_id !== $currentSessionId) {
            $user->update(['active_session_id' => null, 'session_last_activity' => null]);
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')
                ->with('session_reason', 'other_device')
                ->with('error', 'Su sesión fue cerrada porque inició sesión desde otro dispositivo.');
        }

        // Actualizar última actividad
        $user->update([
            'active_session_id' => $currentSessionId,
            'session_last_activity' => now(),
        ]);

        return $next($request);
    }
}
