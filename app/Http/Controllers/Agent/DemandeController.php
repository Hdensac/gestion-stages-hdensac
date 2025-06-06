<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\DemandeStage;
use App\Models\Structure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\AffectationResponsableStructure;

class DemandeController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = DemandeStage::query()
                ->with(['stagiaire.user', 'structure'])
                ->latest();

            // Filtre par recherche
            if ($request->filled('search')) {
                $search = trim($request->search);
                $query->where(function($q) use ($search) {
                    $q->whereHas('stagiaire.user', function($query) use ($search) {
                        $terms = explode(' ', $search);
                        $query->where(function($q) use ($terms) {
                            foreach($terms as $term) {
                                $q->where(function($q) use ($term) {
                                    $q->where('nom', 'like', "%{$term}%")
                                      ->orWhere('prenom', 'like', "%{$term}%")
                                      ->orWhere('email', 'like', "%{$term}%");
                                });
                            }
                        });
                    })
                    ->orWhereHas('structure', function($query) use ($search) {
                        $query->where('libelle', 'like', "%{$search}%");
                    });
                });
            }

            // Filtre par statut
            if ($request->filled('status') && in_array($request->status, ['En attente','En cours' ,'Approuvée', 'Refusée'])) {
                $query->where('statut', $request->status);
            }

            // Filtre par structure
            if ($request->filled('structure_id')) {
                $query->where('structure_id', $request->structure_id);
            }

            $demandes = $query->paginate(10)->withQueryString();

            // Récupérer uniquement les structures principales (parent_id = null) pour le filtre
            $structures = Structure::select('id', 'libelle', 'sigle')
                ->whereNull('parent_id')
                ->orderBy('libelle')
                ->get()
                ->map(function($structure) {
                    return [
                        'id' => $structure->id,
                        'libelle' => $structure->sigle ? $structure->sigle . ' - ' . $structure->libelle : $structure->libelle
                    ];
                });

            return Inertia::render('Agent/Demandes/Index', [
                'demandes' => $demandes,
                'structures' => $structures,
                'filters' => $request->only(['search', 'status', 'structure_id'])
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la recherche des demandes', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'search' => $request->search,
                'status' => $request->status,
                'structure_id' => $request->structure_id
            ]);

            return Inertia::render('Agent/Demandes/Index', [
                'demandes' => [],
                'structures' => [],
                'filters' => $request->only(['search', 'status', 'structure_id']),
                'error' => 'Une erreur est survenue lors de la recherche.'
            ]);
        }
    }

    public function show(DemandeStage $demande)
    {
        $demande->load(['stagiaire.user', 'structure', 'membres.user']);
        $structures = Structure::select('id', 'libelle', 'sigle')
            ->whereNull('parent_id')
            ->orderBy('libelle')
            ->get()
            ->map(function($structure) {
                return [
                    'id' => $structure->id,
                    'libelle' => $structure->sigle ? $structure->sigle . ' - ' . $structure->libelle : $structure->libelle
                ];
            });

        return Inertia::render('Agent/Demandes/Show', [
            'demande' => $demande,
            'structures' => $structures,
            'membres' => $demande->nature === 'Groupe' ? $demande->membres : []
        ]);
    }

    public function approve(DemandeStage $demande)
    {
        try {
            if ($demande->statut !== 'En attente') {
                return back()->with('error', 'Cette demande ne peut plus être approuvée.');
            }

            $demande->update([
                'statut' => 'Approuvée',
                'date_traitement' => now()
            ]);

            // TODO: Envoyer un email de confirmation au stagiaire

            return redirect()->route('agent.demandes.show', $demande)
                ->with('success', 'La demande a été approuvée avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'approbation de la demande', [
                'demande_id' => $demande->id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Une erreur est survenue lors de l\'approbation de la demande.');
        }
    }

    public function reject(DemandeStage $demande)
    {
        try {
            if ($demande->statut !== 'En attente') {
                return back()->with('error', 'Cette demande ne peut plus être refusée.');
            }

            $demande->update([
                'statut' => 'Refusée',
                'date_traitement' => now()
            ]);

            // TODO: Envoyer un email de notification au stagiaire

            return redirect()->route('agent.demandes.show', $demande)
                ->with('success', 'La demande a été refusée.');
        } catch (\Exception $e) {
            Log::error('Erreur lors du refus de la demande', [
                'demande_id' => $demande->id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Une erreur est survenue lors du refus de la demande.');
        }
    }

    public function affecter(Request $request, DemandeStage $demande)
    {
        $request->validate([
            'structure_id' => 'required|exists:structures,id'
        ]);

        try {
            // Récupérer la structure
            $structure = Structure::findOrFail($request->structure_id);

            // Mettre à jour le statut de la demande en "En cours"
            $demande->update([
                'statut' => 'En cours'
            ]);

            // Vérifier si une affectation existe déjà pour cette demande et cette structure
            $existingAffectation = AffectationResponsableStructure::where('id_demande_stages', $demande->id)
                ->where('structure_id', $request->structure_id)
                ->exists();

            if (!$existingAffectation) {
                // Créer une nouvelle affectation seulement si elle n'existe pas déjà
                AffectationResponsableStructure::create([
                    'structure_id' => $request->structure_id,
                    'id_demande_stages' => $demande->id,
                    'date_affectation' => now(),
                ]);
            }

            // Utiliser une session flash unique pour éviter les doublons
            $flashKey = 'affectation_' . $demande->id . '_' . $request->structure_id;
            if (!session()->has($flashKey)) {
                session()->put($flashKey, true);
                return redirect()->back()->with('success', "La demande '{$demande->code_suivi}' a été affectée à la structure '{$structure->sigle}' avec succès");
            }

            return redirect()->back();
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'affectation de la structure', [
                'demande_id' => $demande->id,
                'structure_id' => $request->structure_id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Une erreur est survenue lors de l\'affectation de la structure.');
        }
    }

    public function refuseDefinitivement(Request $request, DemandeStage $demande)
    {
        // Vérifier que la demande est bien en attente de réaffectation
        if ($demande->statut !== 'A réaffecter') {
            return back()->with('error', 'Seules les demandes à réaffecter peuvent être refusées définitivement.');
        }

        $validated = $request->validate([
            'motif_refus' => 'required|string|max:500'
        ]);

        $demande->update([
            'statut' => 'Refusée',
            'date_traitement' => now(),
            'motif_refus' => $validated['motif_refus'],
        ]);

        // Charger les relations nécessaires pour l'email
        $demande->load(['stagiaire.user', 'structure', 'membres.user']);

        // Envoyer le mail au stagiaire
        try {
            Mail::to($demande->stagiaire->user->email)
                ->send(new \App\Mail\DemandeRefusMail($demande, $demande->stagiaire->user, $validated['motif_refus']));
            if ($demande->nature === 'Groupe') {
                foreach ($demande->membres as $membre) {
                    if ($membre['user'] && $membre['user']['email'] !== $demande->stagiaire->user->email) {
                        Mail::to($membre['user']['email'])
                            ->send(new \App\Mail\DemandeRefusMail($demande, $membre['user'], $validated['motif_refus']));
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi de l\'email de refus définitif', [
                'error' => $e->getMessage(),
                'demande_id' => $demande->id
            ]);
        }

        // Envoyer la notification au stagiaire et membres
        try {
            if ($demande->stagiaire && $demande->stagiaire->user) {
                $demande->stagiaire->user->notify(new \App\Notifications\StagiaireNotification(
                    'Votre demande de stage a été refusée définitivement.',
                    route('mes.demandes')
                ));
            }
            if ($demande->nature === 'Groupe' && $demande->membres) {
                foreach ($demande->membres as $membre) {
                    if ($membre['user'] && $demande->stagiaire && $demande->stagiaire->user && $membre['user']['id'] !== $demande->stagiaire->user->id) {
                        $membre['user']->notify(new \App\Notifications\StagiaireNotification(
                            'La demande de stage de votre groupe a été refusée définitivement.',
                            route('mes.demandes')
                        ));
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi de la notification Laravel (refus définitif)', [
                'error' => $e->getMessage(),
                'demande_id' => $demande->id
            ]);
        }

        return redirect()->route('agent.demandes.show', $demande)
            ->with('success', 'La demande a été refusée définitivement et le stagiaire a été notifié.');
    }
}