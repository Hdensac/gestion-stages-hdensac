<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Structure;

class CheckStructureResponsable
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $sigle): Response
    {
        $user = $request->user();
        
        // Vérifier si l'utilisateur est connecté et a un agent associé
        if (!$user || !$user->agent) {
            return redirect()->route('dashboard')->with('error', 'Accès non autorisé. Vous devez être un agent.');
        }
        
        // Vérifier si l'agent est un RS
        if ($user->agent->role_agent !== 'RS') {
            return redirect()->route('dashboard')->with('error', 'Accès non autorisé. Vous devez être un Responsable de Structure (RS).');
        }
        
        // Vérifier si l'agent est responsable d'une structure avec le sigle spécifié
        $isResponsable = Structure::where('responsable_id', $user->agent->id)
            ->where('sigle', $sigle)
            ->exists();
            
        if (!$isResponsable) {
            return redirect()->route('dashboard')->with('error', 'Accès non autorisé. Vous n\'êtes pas responsable de la structure ' . $sigle . '.');
        }
        
        return $next($request);
    }
}
