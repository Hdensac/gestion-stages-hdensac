<x-mail::message>
# Évaluation de votre stage

Bonjour {{ $stagiaire->prenom }} {{ $stagiaire->nom }},

Votre maître de stage a évalué votre stage à {{ $stage->structure->libelle ?? 'la structure d\'accueil' }}.

## Résultats de l'évaluation

**Note totale :** {{ $evaluation->note_totale }}/20

### Détail des critères d'évaluation :
- **Ponctualité / Présences :** {{ $evaluation->ponctualite }}/2
- **Motivation / Initiative :** {{ $evaluation->motivation }}/2
- **Capacités d'apprendre :** {{ $evaluation->capacite_apprendre }}/2
- **Qualité du travail :** {{ $evaluation->qualite_travail }}/2
- **Rapidité d'exécution :** {{ $evaluation->rapidite_execution }}/2
- **Jugement :** {{ $evaluation->jugement }}/2
- **Esprit de motivation :** {{ $evaluation->esprit_motivation }}/2
- **Esprit de collaboration :** {{ $evaluation->esprit_collaboration }}/2
- **Sens de responsabilité :** {{ $evaluation->sens_responsabilite }}/2
- **Communication :** {{ $evaluation->communication }}/2

@if($evaluation->commentaire_general)
### Commentaire général :
{{ $evaluation->commentaire_general }}
@endif

<x-mail::button :url="route('stagiaire.stages.show', $stage->id)">
Voir les détails du stage
</x-mail::button>

Si vous avez des questions concernant cette évaluation, n'hésitez pas à en discuter avec votre maître de stage via la plateforme.

Cordialement,<br>
{{ config('app.name') }}
</x-mail::message>
