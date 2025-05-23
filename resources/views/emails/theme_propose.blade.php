<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Notification de thème de stage</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #e3f2fd;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #1976d2;
        }
        .content {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .footer {
            font-size: 12px;
            color: #777;
            border-top: 1px solid #eee;
            padding-top: 15px;
            margin-top: 20px;
        }
        h1 {
            color: #1976d2;
            font-size: 24px;
            margin-top: 0;
        }
        .signature {
            margin-top: 30px;
            font-style: italic;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #1976d2;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Proposition de thème pour votre stage</h1>
        <p>Bonjour {{ $stagiaire->prenom }} {{ $stagiaire->nom }},</p>
    </div>
    <div class="content">
        <p>Votre maître de stage vient de {{ $theme->etat === 'Modifié' ? 'modifier' : 'proposer' }} un thème pour votre stage à <strong>{{ $stage->structure->libelle ?? 'la structure d\'accueil' }}</strong>.</p>
        <h3>Détails du thème</h3>
        <ul>
            <li><strong>Intitulé :</strong> {{ $theme->intitule }}</li>
            <li><strong>Description :</strong> {{ $theme->description }}</li>
            @if($theme->mots_cles)
            <li><strong>Mots-clés :</strong> {{ $theme->mots_cles }}</li>
            @endif
            <li><strong>État :</strong> {{ $theme->etat }}</li>
        </ul>
        @if($theme->etat === 'Modifié')
            <p>Veuillez prendre connaissance des modifications apportées au thème de votre stage.</p>
        @else
            <p>Veuillez prendre connaissance du thème proposé pour votre stage.</p>
        @endif
        <a href="{{ route('stagiaire.stages.show', $stage->id) }}" class="btn">Voir les détails du stage</a>
    </div>
    <div class="signature">
        <p>
            Cordialement,<br>
            {{ $stage->structure->libelle ?? config('app.name') }}
        </p>
    </div>
    <div class="footer">
        <p>Ce message concerne votre stage à {{ $stage->structure->libelle ?? 'Structure non spécifiée' }}.</p>
        <p>Veuillez ne pas répondre directement à cet email. Pour toute question, contactez votre maître de stage via la plateforme.</p>
    </div>
</body>
</html>
