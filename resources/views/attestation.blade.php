<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Attestation de Stage</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .attestation-container { border: 2px solid #4F46E5; border-radius: 16px; padding: 32px; max-width: 700px; margin: auto; }
        h1 { color: #4F46E5; text-align: center; }
        .info { margin: 24px 0; }
        .label { font-weight: bold; color: #374151; }
        .footer { margin-top: 48px; text-align: right; color: #6B7280; }
        .logo { width: 120px; margin-bottom: 24px; }
    </style>
</head>
<body>
    <div class="attestation-container">
        <img src="{{ public_path('images/logo_ministere.png') }}" alt="Logo" class="logo">
        <h1>Attestation de Stage</h1>
        <div class="info">
            <span class="label">Stagiaire :</span> {{ $stagiaire->user->nom }} {{ $stagiaire->user->prenom }}<br>
            <span class="label">Email :</span> {{ $stagiaire->user->email }}<br>
            <span class="label">Téléphone :</span> {{ $stagiaire->user->telephone }}<br>
            <span class="label">Université :</span> {{ $stagiaire_principal->universite }}<br>
            <span class="label">Filière :</span> {{ $stagiaire_principal->filiere }}<br>
            <span class="label">Niveau d'étude :</span> {{ $stagiaire_principal->niveau_etude }}<br>
        </div>
        <div class="info">
            <span class="label">Structure d'accueil :</span> {{ $structure->libelle }}<br>
            <span class="label">Période du stage :</span> du @php echo \Carbon\Carbon::parse($stage->date_debut)->format('d/m/Y'); @endphp au @php echo \Carbon\Carbon::parse($stage->date_fin)->format('d/m/Y'); @endphp<br>
            <span class="label">Type de stage :</span> {{ $stage->type }}<br>
            <span class="label">Statut :</span> {{ $stage->statut }}<br>
        </div>
        <div class="footer">
            Fait à {{ $structure->libelle }}, le {{ now()->format('d/m/Y') }}<br>
            <br>
            <span style="font-weight: bold;">Le Responsable de la Structure</span>
        </div>
    </div>
</body>
</html> 