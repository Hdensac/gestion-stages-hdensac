<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;
use App\Models\Structure;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user();
        $isDpafResponsable = false;
        $isRSAgent = false;

        // Vérifier si l'utilisateur est connecté et a un agent associé
        if ($user && $user->agent) {
            // Vérifier si l'agent est un RS
            $isRSAgent = $user->agent->role_agent === 'RS';

            // Vérifier si l'agent est responsable de la structure DPAF
            if ($isRSAgent) {
                $isDpafResponsable = Structure::where('responsable_id', $user->agent->id)
                    ->where('sigle', 'DPAF')
                    ->exists();
            }
        }

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $user,
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error'   => fn () => $request->session()->get('error'),
                'toast'   => fn () => $request->session()->get('toast'),
            ],
            'isDpafResponsable' => $isDpafResponsable,
            'isRSAgent' => $isRSAgent,
        ];
    }
}
