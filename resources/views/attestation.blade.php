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
        @media print {
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
    <button onclick="window.print()" class="no-print" style="float: right; margin-bottom: 20px; padding: 8px 18px; font-size: 1em; background: #4F46E5; color: #fff; border: none; border-radius: 6px; cursor: pointer;">
        Imprimer / Télécharger en PDF
    </button>
    <div class="attestation-container">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
            <!-- Logo du ministère -->
            <div style="width: 300px;">
                <img src="{{ asset('images/logoministere.png') }}" alt="Logo Ministère" style="width: 300px;">
            </div>
            <!-- Informations du ministère -->
            <div style="text-align: right; font-size: 0.98em; line-height: 1.4;">
                <span style="font-size: 0.95em;">368, Avenue Pape Jean Paul II<br>01 BP 302 COTONOU<br>Tél : 21 30 10 20 – Fax : 21 30 18 51<br><a href="http://www.finances.bj" style="color: #222; text-decoration: underline;">www.finances.bj</a></span><br>
                <span style="font-weight: bold;">Cotonou, le {{ now()->format('d/m/Y') }}</span>
            </div>
        </div>
        <!-- Bloc titres institutionnels -->
        <div style="text-align: left; margin-bottom: 18px;">
            <span style="font-weight: bold; font-size: 1.1em;">SECRÉTARIAT GÉNÉRAL<br>DU MINISTÈRE</span><br>
            <span style="font-size: 1em;">--------------------</span><br>
            <span style="font-weight: bold; font-size: 1.1em;">{{ mb_strtoupper($libelle_structure, 'UTF-8') }}<br>{{ $sigle_structure ? '(' . mb_strtoupper($sigle_structure, 'UTF-8') . ')' : '' }}</span>
        </div>
        <!-- Numéro d'attestation (optionnel) -->
        @isset($numero_attestation)
            <div style="margin-bottom: 18px; font-size: 1.05em;">
                N° <span style="font-weight: bold;">{{ $numero_attestation }}</span>/MEF/SGM/DSI/RSCP/SA
            </div>
        @endisset
        <h1>ATTESTATION D'EFFECTIVITE DE STAGE</h1>
        <div class="content">
            Je soussigné <span class="bold">{{ mb_strtoupper($nom_rs, 'UTF-8') }}</span>, Responsable de la structure <span class="bold">{{ mb_strtoupper($libelle_structure, 'UTF-8') }}{{ $sigle_structure ? ' (' . mb_strtoupper($sigle_structure, 'UTF-8') . ')' : '' }}</span>,
            atteste que Monsieur <span class="bold">{{ $stagiaire->user->nom }} {{ $stagiaire->user->prenom }}</span>, a été stagiaire à la <span class="bold">{{ mb_strtoupper($libelle_structure, 'UTF-8') }}{{ $sigle_structure ? ' (' . mb_strtoupper($sigle_structure, 'UTF-8') . ')' : '' }}</span>.
            <br><br>
            Il est étudiant en <span class="bold">{{ $stagiaire_principal->niveau_etude }}</span> en <span class="bold">{{ $stagiaire_principal->filiere }}</span> à <span class="bold">{{ $stagiaire_principal->universite }}</span>.
            <br><br>
            Mis à la disposition de la <span class="bold">{{ mb_strtoupper($libelle_structure, 'UTF-8') }}{{ $sigle_structure ? ' (' . mb_strtoupper($sigle_structure, 'UTF-8') . ')' : '' }}</span> pour un stage <span class="bold">{{ $stage->type }}</span>, pratique et bénévole d'une durée de
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
            Fait à {{ mb_strtoupper($libelle_structure, 'UTF-8') }}, le {{ now()->format('d/m/Y') }}
        </div>
        <div class="signature">
            Directeur de la structure {{ $sigle_structure }}<br>
            
            <span class="bold">{{ $nom_rs }}</span>
        </div>
    </div>
</body>
</html> 