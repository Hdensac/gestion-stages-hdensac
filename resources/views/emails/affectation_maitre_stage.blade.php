@php
$stagiaireNom = $stagiaire->user->prenom . ' ' . $stagiaire->user->nom;
$msNom = $maitreStage->prenom . ' ' . $maitreStage->nom;
@endphp

<p>Bonjour {{ $stagiaireNom }},</p>

@if($isReaffectation)
<p>Nous vous informons que votre maître de stage pour le stage <strong>{{ $stage->themeStage->intitule ?? 'sans intitulé' }}</strong> a été <strong>réaffecté</strong>.</p>
@else
<p>Nous vous informons que vous avez été affecté à un maître de stage pour le stage <strong>{{ $stage->themeStage->intitule ?? 'sans intitulé' }}</strong>.</p>
@endif

<p>Votre maître de stage est désormais :</p>
<ul>
    <li><strong>Nom :</strong> {{ $msNom }}</li>
    <li><strong>Email :</strong> {{ $maitreStage->email }}</li>
</ul>

@if($isReaffectation)
<p>Cette modification prend effet à partir du {{ \Carbon\Carbon::parse($stage->date_debut)->format('d/m/Y') }}.</p>
@endif

<p>Pour toute question, vous pouvez le contacter directement.</p>

<p>Cordialement,<br>L'équipe Gestion des Stages</p> 