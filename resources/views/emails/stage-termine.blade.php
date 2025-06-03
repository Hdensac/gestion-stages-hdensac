@component('mail::message')
# Stage terminé

Le stage "{{ $stage->intitule }}" a été marqué comme terminé par le Maître de Stage.

**Détails du stage :**
- Intitulé : {{ $stage->intitule }}
- Date de début : {{ $stage->date_debut->format('d/m/Y') }}
- Date de fin : {{ $stage->date_fin->format('d/m/Y') }}
- Date de confirmation : {{ $stage->date_confirmation_ms->format('d/m/Y') }}

@component('mail::button', ['url' => route('stagiaire.stages.show', $stage->id)])
Voir les détails du stage
@endcomponent

Merci,<br>
{{ config('app.name') }}
@endcomponent 