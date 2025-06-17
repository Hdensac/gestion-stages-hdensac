<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Attestation d'Effectivité de Stage</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; margin: 40px; }
        .attestation-container { border: 2px solid #4F46E5; border-radius: 16px; padding: 48px 32px; max-width: 800px; margin: auto; }
        h1 { color: #222; text-align: center; font-size: 2.1em; font-weight: bold; text-decoration: underline; margin-bottom: 40px; }
        .content { font-size: 1.15em; color: #222; line-height: 1.7; margin-bottom: 40px; }
        .signature { margin-top: 60px; text-align: right; font-size: 1.1em; }
        .stamp { width: 120px; margin-top: 16px; }
        .bold { font-weight: bold; }
        .center { text-align: center; }
        .footer { margin-top: 40px; color: #444; font-size: 0.95em; text-align: right; }
    </style>
</head>
<body>
    <div class="attestation-container">
        <h1>ATTESTATION D'EFFECTIVITE DE STAGE</h1>
        <div class="content">
            Je soussigné <span class="bold">{{ strtoupper($nom_rs) }}</span>, Responsable de la structure <span class="bold">{{ $libelle_structure }} ({{ $sigle_structure }})</span>,
            atteste que Monsieur <span class="bold">{{ $stagiaire->user->nom }} {{ $stagiaire->user->prenom }}</span>, a été stagiaire à la <span class="bold">{{ $libelle_structure }} ({{ $sigle_structure }})</span>.
            <br><br>
            Il est étudiant en <span class="bold">{{ $stagiaire_principal->niveau_etude }}</span> en <span class="bold">{{ $stagiaire_principal->filiere }}</span> à <span class="bold">{{ $stagiaire_principal->universite }}</span>.
            <br><br>
            Mis à la disposition de la <span class="bold">{{ $libelle_structure }} ({{ $sigle_structure }})</span> pour un stage <span class="bold">{{ $stage->type }}</span>, pratique et bénévole d'une durée de
            @php
                $dateDebut = \Carbon\Carbon::parse($stage->date_debut);
                $dateFin = \Carbon\Carbon::parse($stage->date_fin);
                $diffJours = $dateDebut->diffInDays($dateFin) + 1;
                $diffMois = $dateDebut->diffInMonths($dateFin);
            @endphp
            @if($diffMois >= 1)
                {{ $diffMois }} mois
            @else
                {{ $diffJours }} jour{{ $diffJours > 1 ? 's' : '' }}
            @endif
            , il a effectué son stage du
            <span class="bold">{{ \Carbon\Carbon::parse($stage->date_debut)->isoFormat('dddd D MMMM YYYY') }}</span> au <span class="bold">{{ \Carbon\Carbon::parse($stage->date_fin)->isoFormat('dddd D MMMM YYYY') }}</span>.
            <br><br>
            En foi de quoi la présente attestation lui est délivrée pour servir et valoir ce que de droit.
        </div>
        <div class="footer">
            Fait à {{ $libelle_structure }}, le {{ now()->format('d/m/Y') }}
        </div>
        <div class="signature">
            Responsable de la structure<br>
            <img src="{{ public_path('images/cachet_dsi.png') }}" alt="Cachet" class="stamp"><br>
            <span class="bold">{{ $nom_rs }}</span>
        </div>
    </div>
</body>
</html> 