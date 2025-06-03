<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StructureController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DemandeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StagiaireController;
use App\Http\Controllers\Api\EmailController;
use App\Http\Controllers\Api\StagiaireMessageController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Admin\RapportController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Redirection du dashboard principal vers le dashboard approprié
Route::get('/dashboard', function () {
    $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->agent) {
            // Si l'agent est un RS, rediriger vers le dashboard RS
            if ($user->agent->role_agent === 'RS') {
                return redirect()->route('agent.rs.dashboard');
            }

            // Si l'agent est un MS, rediriger vers le dashboard MS
            if ($user->agent->role_agent === 'MS') {
                return redirect()->route('agent.ms.dashboard');
            }
        }

        if ($user->role === 'stagiaire') {
            return redirect()->route('stagiaire.dashboard');
        }

        // Redirection par défaut
        return redirect()->route('login');
    })->name('dashboard');

    // Routes de profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin routes
    Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function() {
        // Dashboard admin avec statistiques
        Route::get('/dashboard', function () {
            $stats = [
                'users' => \App\Models\User::count(),
                'structures' => \App\Models\Structure::count(),
                'stagiaires' => \App\Models\Stagiaire::count(),
                'agents' => \App\Models\Agent::count(),
            ];

            $recentStagiaires = DB::table('stagiaires')->orderBy('created_at', 'desc')->take(2)->get();
            $recentStructures = DB::table('structures')->orderBy('created_at', 'desc')->take(1)->get();
            $lastDemande = DB::table('demande_stages')->orderBy('created_at', 'desc')->first();

            $recentActivities = [];

            // Ajout de la dernière demande de stage
            if ($lastDemande) {
                $stagiaire = DB::table('users')
                    ->join('stagiaires', 'users.id', '=', 'stagiaires.user_id')
                    ->where('stagiaires.id_stagiaire', $lastDemande->stagiaire_id)
                    ->select('users.nom', 'users.prenom')
                    ->first();
                $structure = null;
                if ($lastDemande->structure_id) {
                    $structureObj = DB::table('structures')->where('id', $lastDemande->structure_id)->first();
                    $structure = $structureObj ? $structureObj->sigle : null;
                }
                $recentActivities[] = [
                    'type' => 'demande',
                    'title' => 'Dernière demande de stage',
                    'user' => $stagiaire ? trim(($stagiaire->prenom ?? '') . ' ' . ($stagiaire->nom ?? '')) : null,
                    'structure' => $structure,
                    'created_at' => $lastDemande->created_at,
                ];
            }

            foreach ($recentStagiaires as $stagiaire) {
                // On va chercher le nom du user associé
                $user = DB::table('users')->where('id', $stagiaire->user_id)->first();
                $userName = $user ? trim(($user->prenom ?? '') . ' ' . ($user->nom ?? '')) : null;

                $recentActivities[] = [
                    'type' => 'stagiaire',
                    'title' => 'Nouveau stagiaire inscrit',
                    'user' => $userName,
                    'created_at' => $stagiaire->created_at,
                ];
            }
            foreach ($recentStructures as $structure) {
                $recentActivities[] = [
                    'type' => 'structure',
                    'title' => 'Nouvelle structure ajoutée',
                    'structure' => $structure->libelle,
                    'created_at' => $structure->created_at,
                ];
            }
            $recentActivities[] = [
                'type' => 'rapport',
                'title' => 'Rapport mensuel généré',
                'created_at' => now()->subDays(2),
            ];

            // Trie par date décroissante
            usort($recentActivities, fn($a, $b) => strtotime($b['created_at']) <=> strtotime($a['created_at']));

            return Inertia::render('Dashboard/Admin', [
                'stats' => $stats,
                'recentActivities' => $recentActivities,
            ]);
        })->name('dashboard');

        // Autres routes admin...
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        Route::get('/structures', [StructureController::class, 'index'])->name('structures.index');
        Route::post('/structures', [StructureController::class, 'store'])->name('structures.store');
        Route::put('/structures/{structure}', [StructureController::class, 'update'])->name('structures.update');
        Route::delete('/structures/{structure}', [StructureController::class, 'destroy'])->name('structures.destroy');

        // Ajout de la route pour récupérer les structures disponibles
        Route::get('/structures/available', [StructureController::class, 'available'])->name('structures.available');

        Route::get('/stagiaires', [StagiaireController::class, 'index'])->name('stagiaires.index');

        // Routes pour les agents
        Route::get('/agents', [AgentController::class, 'index'])->name('agents.index');
        Route::post('/agents', [AgentController::class, 'store'])->name('agents.store');
        Route::put('/agents/{agent}', [AgentController::class, 'update'])->name('agents.update');
        Route::delete('/agents/{agent}', [AgentController::class, 'destroy'])->name('agents.destroy');
    });

    // Routes pour les demandes de stage
    Route::post('/demande-stages', [DemandeController::class, 'store'])->name('demande_stages.store');
    Route::get('/mes-demandes', [DemandeController::class, 'index'])->name('mes.demandes');
    Route::get('/mes-demandes/{id}', [DemandeController::class, 'show'])->name('mes.demandes.show');
    Route::delete('/mes-demandes/{id}', [DemandeController::class, 'destroy'])->name('mes.demandes.annuler');
    Route::post('/demandes/recherche', [DemandeController::class, 'findByCode'])->name('demandes.search');
    Route::get('/demandes/recherche', [DemandeController::class, 'findByCode'])->name('demandes.search.get');
    Route::get('/recherche-code', [App\Http\Controllers\DemandeController::class, 'create'])->name('recherche.code');

    // Routes pour les emails
    Route::post('/api/emails/demande-confirmation', [EmailController::class, 'sendDemandeConfirmation']);
    Route::get('/api/emails/check-config', [EmailController::class, 'checkEmailConfig']);

    // Routes pour les stagiaires
    Route::resource('stagiaires', StagiaireController::class);

    // Routes pour les agents responsables de la structure DPAF
    Route::prefix('agent')->name('agent.')->middleware(['auth', 'verified'])->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Agent\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/demandes', [App\Http\Controllers\Agent\DemandeController::class, 'index'])->name('demandes');
        Route::get('/demandes/{demande}', [App\Http\Controllers\Agent\DemandeController::class, 'show'])->name('demandes.show');
        Route::post('/demandes/{demande}/approve', [App\Http\Controllers\Agent\DemandeController::class, 'approve'])->name('demandes.approve');
        Route::post('/demandes/{demande}/reject', [App\Http\Controllers\Agent\DemandeController::class, 'reject'])->name('demandes.reject');
        Route::get('/structures', [App\Http\Controllers\Agent\StructureController::class, 'index'])->name('structures');
        Route::get('/structures/{structure}', [App\Http\Controllers\Agent\StructureController::class, 'show'])->name('structures.show');
        Route::get('/stagiaires', [App\Http\Controllers\Agent\StagiaireController::class, 'index'])->name('stagiaires.index');
        Route::get('/stagiaires/{stagiaire}', [App\Http\Controllers\Agent\StagiaireController::class, 'show'])->name('stagiaires.show');
        Route::post('/demandes/{demande}/affecter', [App\Http\Controllers\Agent\DemandeController::class, 'affecter'])->name('demandes.affecter');

        // Routes pour les agents RS
        Route::prefix('rs')->name('rs.')->middleware(['auth'])->group(function () {
            Route::get('/dashboard', [App\Http\Controllers\Agent\RS\DashboardController::class, 'index'])->name('dashboard');
            Route::get('/demandes', [App\Http\Controllers\Agent\RS\DemandeController::class, 'index'])->name('demandes');
            Route::get('/demandes/{demande}', [App\Http\Controllers\Agent\RS\DemandeController::class, 'show'])->name('demandes.show');
            Route::post('/demandes/{demande}/approve', [App\Http\Controllers\Agent\RS\DemandeController::class, 'approve'])->name('demandes.approve');
            Route::post('/demandes/{demande}/reject', [App\Http\Controllers\Agent\RS\DemandeController::class, 'reject'])->name('demandes.reject');
            Route::get('/responsable-agents', [App\Http\Controllers\Agent\RS\DemandeController::class, 'getResponsableAgents'])->name('responsable-agents');
            Route::post('/demandes/{demande}/affecter-maitre', [App\Http\Controllers\Agent\RS\DemandeController::class, 'affecterMaitreStage'])->name('demandes.affecter-maitre');
            // Routes pour les stages
            Route::get('/stages', [App\Http\Controllers\Agent\RS\StageController::class, 'index'])->name('stages');
            Route::get('/stages/{stage}', [App\Http\Controllers\Agent\RS\StageController::class, 'show'])->name('stages.show');
            // CRUD agents sans rôle
            Route::resource('agents', App\Http\Controllers\Agent\RS\AgentController::class);
            // ROUTE PERSONNALISÉE D'ABORD
            Route::get('organigramme/sous-structures', [App\Http\Controllers\Agent\RS\StructureOrganigrammeController::class, 'sousStructures'])
                ->name('organigramme.sous-structures');
            // PUIS LA RESSOURCE
            Route::resource('organigramme', App\Http\Controllers\Agent\RS\StructureOrganigrammeController::class)
                ->parameters(['organigramme' => 'structure']);
            Route::post('organigramme/{structure}/assign-agent', [App\Http\Controllers\Agent\RS\StructureOrganigrammeController::class, 'assignAgent'])->name('organigramme.assign-agent');
        });

        // Routes pour les maîtres de stage (MS)
        Route::prefix('ms')->name('ms.')->middleware(['auth'])->group(function () {
            Route::get('/dashboard', [App\Http\Controllers\Agent\MS\DashboardController::class, 'index'])->name('dashboard');
            Route::get('/stages', [App\Http\Controllers\Agent\MS\StageController::class, 'index'])->name('stages');
            Route::get('/stages/{stage}', [App\Http\Controllers\Agent\MS\StageController::class, 'show'])->name('stages.show');
            Route::post('/stages/{stage}/update-status', [App\Http\Controllers\Agent\MS\StageController::class, 'updateStatus'])->name('stages.update-status');
            Route::post('/stages/{stage}/theme', [App\Http\Controllers\Agent\MS\StageController::class, 'storeTheme'])->name('stages.theme.store');
            Route::post('/stages/{stage}/valider-theme', [App\Http\Controllers\Agent\MS\StageController::class, 'validerTheme'])->name('stages.valider-theme');
            Route::post('/stages/{stage}/refuser-theme', [App\Http\Controllers\Agent\MS\StageController::class, 'refuserTheme'])->name('stages.refuser-theme');
            Route::post('/stages/{stage}/noter', [App\Http\Controllers\Agent\MS\StageController::class, 'noter'])->name('stages.noter');
            Route::get('/stages/{stage}/maitres-stage-substructures', [App\Http\Controllers\Agent\MS\StageController::class, 'getMaitresStageSubstructures'])->name('stages.maitres-stage-substructures');
            Route::post('/stages/{stage}/reaffecter', [App\Http\Controllers\Agent\MS\StageController::class, 'reaffecter'])->name('stages.reaffecter');
            Route::get('/stages/{stage}/themes-proposes', [App\Http\Controllers\Agent\MS\StageController::class, 'getThemesProposes'])->name('stages.themes-proposes');
            Route::post('/stages/{stage}/themes/{theme}/action', [App\Http\Controllers\Agent\MS\StageController::class, 'validerOuRefuserTheme'])->name('stages.theme-action');
            // Route pour récupérer l'évaluation individuelle d'un membre du groupe
            Route::get('/stages/{stage}/evaluations/{membre}', [App\Http\Controllers\Agent\MS\StageController::class, 'getEvaluationMembre']);

            // Route pour envoyer un message à un stagiaire
            Route::post('/stages/send-message', [StagiaireMessageController::class, 'sendMessage'])->name('stages.send-message');

            Route::post('/stages/{stage}/confirmer-fin', [App\Http\Controllers\Agent\MS\StageController::class, 'confirmerFinStage'])
                ->name('ms.stages.confirmer-fin');
        });
    });

    // Routes pour les stagiaires
    Route::prefix('stagiaire')->name('stagiaire.')->middleware(['auth', 'verified'])->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Stagiaire\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/demandes', [App\Http\Controllers\Stagiaire\DemandeController::class, 'index'])->name('demandes');
        Route::get('/demandes/{demande}', [App\Http\Controllers\Stagiaire\DemandeController::class, 'show'])->name('demandes.show');
        // Routes pour les stages
        Route::get('/stages', [App\Http\Controllers\Stagiaire\StageController::class, 'index'])->name('stages');
        Route::get('/stages/{stage}', [App\Http\Controllers\Stagiaire\StageController::class, 'show'])->name('stages.show');
        Route::post('/stages/{stage}/themes', [App\Http\Controllers\Stagiaire\StageController::class, 'proposerTheme'])->name('stages.proposer-theme');
    });

    // Route pour télécharger le rapport mensuel
    Route::get('/admin/rapport-mensuel', [RapportController::class, 'telecharger'])->name('admin.rapport-mensuel');

    Route::post('/stagiaire/notifications/{id}/mark-as-read', [
        \App\Http\Controllers\Stagiaire\NotificationController::class,
        'markAsRead'
    ])->name('stagiaire.notifications.markAsRead');
});

require __DIR__.'/auth.php';