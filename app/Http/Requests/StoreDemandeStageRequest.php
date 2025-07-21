<?php

namespace App\Http\Requests;

use App\Models\DemandeStage;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreDemandeStageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->isStagiaire();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telephone' => 'required|string|max:20',
            'universite' => 'nullable|required_if:type,Académique|string|max:255',
            'filiere' => 'required|string|max:255',
            'niveau_etude' => 'required|string|max:255',
            'date_debut' => [
                'required',
                'date',
                'after_or_equal:today',
                function ($attribute, $value, $fail) {
                    $this->validatePeriodAvailability($attribute, $value, $fail);
                }
            ],
            'date_fin' => 'required|date|after:date_debut',
            'structure_id' => 'nullable|exists:structures,id',
            'type' => 'required|in:Académique,Professionnelle',
            'nature' => 'required|in:Individuel,Groupe',
            'lettre_cv_path' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'visage_path' => 'nullable|file|mimes:jpg,jpeg,png|max:1024',
            'membres' => 'nullable|array|required_if:nature,Groupe',
            'membres.*' => [
                'integer',
                'exists:users,id',
                'different:' . Auth::id(),
                function ($attribute, $value, $fail) {
                    $this->validateMemberAvailability($attribute, $value, $fail);
                }
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'date_debut.after_or_equal' => 'La date de début doit être aujourd\'hui ou dans le futur.',
            'date_fin.after' => 'La date de fin doit être postérieure à la date de début.',
            'membres.required_if' => 'Vous devez sélectionner au moins un membre pour un stage de groupe.',
            'membres.*.different' => 'Vous ne pouvez pas vous ajouter comme membre du groupe.',
            'membres.*.exists' => 'Un des membres sélectionnés n\'existe pas.',
            'lettre_cv_path.mimes' => 'Le fichier doit être au format PDF, DOC ou DOCX.',
            'lettre_cv_path.max' => 'Le fichier ne doit pas dépasser 2 Mo.',
            'visage_path.mimes' => 'La photo doit être au format JPG, JPEG ou PNG.',
            'visage_path.max' => 'La photo ne doit pas dépasser 1 Mo.',
        ];
    }

    /**
     * Valide que le stagiaire n'a pas de conflit de période
     */
    protected function validatePeriodAvailability($attribute, $value, $fail)
    {
        $user = Auth::user();
        
        if (!$user->stagiaire) {
            $fail('Profil stagiaire non trouvé.');
            return;
        }

        $dateDebut = $value;
        $dateFin = $this->input('date_fin');

        if (!$dateFin) {
            return; // La validation de date_fin se chargera de cette erreur
        }

        $conflict = DemandeStage::checkPeriodConflict(
            $user->stagiaire->id_stagiaire,
            $dateDebut,
            $dateFin
        );

        if ($conflict['hasConflict']) {
            $message = $this->generateConflictMessage($conflict);
            $fail($message);
        }
    }

    /**
     * Valide qu'un membre du groupe est disponible pour la période
     */
    protected function validateMemberAvailability($attribute, $value, $fail)
    {
        $dateDebut = $this->input('date_debut');
        $dateFin = $this->input('date_fin');

        if (!$dateDebut || !$dateFin) {
            return; // Les autres validations s'occuperont de ces erreurs
        }

        $member = User::find($value);
        
        if (!$member || !$member->isStagiaire()) {
            $fail('Le membre sélectionné n\'est pas un stagiaire valide.');
            return;
        }

        $availability = $member->checkStagiaireAvailability($dateDebut, $dateFin);

        if (!$availability['available']) {
            $memberName = $member->name . ' ' . $member->prenom;
            $fail("$memberName n'est pas disponible pour cette période : {$availability['reason']}");
        }
    }

    /**
     * Génère un message d'erreur lisible pour les conflits de période
     */
    protected function generateConflictMessage($conflict)
    {
        $data = $conflict['conflictData'];
        
        if ($conflict['conflictType'] === 'demande') {
            return "Vous avez déjà une demande {$data['statut']} (Code: {$data['code_suivi']}) " .
                   "pour la période du {$data['date_debut']} au {$data['date_fin']} " .
                   "dans la structure \"{$data['structure']}\". " .
                   "Veuillez modifier les dates ou attendre le traitement de votre demande actuelle.";
        } else {
            return "Vous avez déjà un stage {$data['statut']} " .
                   "pour la période du {$data['date_debut']} au {$data['date_fin']} " .
                   "dans la structure \"{$data['structure']}\". " .
                   "Veuillez modifier les dates ou attendre la fin de votre stage actuel.";
        }
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // S'assurer que les membres sont uniques
        if ($this->has('membres') && is_array($this->membres)) {
            $this->merge([
                'membres' => array_unique($this->membres)
            ]);
        }
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Validation supplémentaire pour les groupes
            if ($this->nature === 'Groupe') {
                $membres = $this->input('membres', []);
                
                if (count($membres) === 0) {
                    $validator->errors()->add('membres', 'Vous devez sélectionner au moins un membre pour un stage de groupe.');
                }
                
                if (count($membres) > 10) { // Limite arbitraire
                    $validator->errors()->add('membres', 'Un groupe ne peut pas contenir plus de 10 membres.');
                }
            }
        });
    }
}
