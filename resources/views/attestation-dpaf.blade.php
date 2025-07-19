<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Attestation de Stage</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; margin: 40px; }
        .attestation-container { border: 2px solid #059669; border-radius: 16px; padding: 48px 32px; max-width: 800px; margin: auto; }
        h1 { color: #222; text-align: center; font-size: 2.1em; font-weight: bold; text-decoration: underline; margin-bottom: 40px; }
        .content { font-size: 1.15em; color: #222; line-height: 1.7; margin-bottom: 40px; }
        .signature { margin-top: 60px; text-align: right; font-size: 1.1em; }
        .stamp { width: 120px; margin-top: 16px; }
        .bold { font-weight: bold; }
        .center { text-align: center; }
        .footer { margin-top: 40px; font-size: 1.1em; }
        .no-print { display: block; }
        @media print { .no-print { display: none; } }
        .dpaf-header { background: linear-gradient(135deg, #059669, #10b981); color: white; padding: 20px; border-radius: 12px; margin-bottom: 30px; }
    </style>
</head>
<body>
    <button onclick="window.print()" class="no-print" style="float: right; margin-bottom: 20px; padding: 8px 18px; font-size: 1em; background: #059669; color: #fff; border: none; border-radius: 6px; cursor: pointer;">
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
                <strong>RÉPUBLIQUE DU BÉNIN</strong><br>
                <em>Fraternité - Justice - Travail</em><br>
                ────────────<br>
                <strong>MINISTÈRE DE L'ÉCONOMIE ET DES FINANCES</strong><br>
                <strong>SECRÉTARIAT GÉNÉRAL</strong><br>
                <strong>DIRECTION DE LA PLANIFICATION ET DE L'ADMINISTRATION FINANCIÈRE</strong>
            </div>
        </div>
        
        <!-- En-tête DPAF -->
        <div class="dpaf-header center">
            <span style="font-weight: bold; font-size: 1.2em;">DIRECTION DE LA PLANIFICATION ET DE L'ADMINISTRATION FINANCIÈRE</span><br>
            <span style="font-size: 1.1em;">(DPAF)</span>
        </div>
        
        <!-- Numéro d'attestation -->
        <div style="margin-bottom: 18px; font-size: 1.05em; text-align: center;">
            N° <span style="font-weight: bold;">{{ $numero_attestation }}</span>
        </div>
        
        <h1>ATTESTATION DE STAGE</h1>
        
        <div class="content">
            Je soussigné <span class="bold">{{ mb_strtoupper($agent_dpaf->user->nom ?? 'DIRECTEUR DPAF', 'UTF-8') }} {{ mb_strtoupper($agent_dpaf->user->prenom ?? '', 'UTF-8') }}</span>, 
            Directeur de la Planification  et de l'Administration Financière,
            <br><br>
            
            <span class="bold">ATTESTE</span> que :
            <br><br>
            
            <span class="bold">{{ mb_strtoupper($stagiaire->user->prenom ?? '', 'UTF-8') }} {{ mb_strtoupper($stagiaire->user->nom ?? '', 'UTF-8') }}</span>,
            né(e) le <span class="bold">{{ $stagiaire->user->date_de_naissance ? \Carbon\Carbon::parse($stagiaire->user->date_de_naissance)->isoFormat('D MMMM YYYY') : 'Non spécifié' }}</span>,
            de sexe <span class="bold">{{ $stagiaire->user->sexe ?? 'Non spécifié' }}</span>,
            étudiant(e) en <span class="bold">{{ $stagiaire->niveau_etude ?? 'Non spécifié' }}</span>
            @if($stagiaire->filiere)
                , filière <span class="bold">{{ $stagiaire->filiere }}</span>
            @endif
            @if($stagiaire->universite)
                à <span class="bold">{{ $stagiaire->universite }}</span>
            @endif
            ,
            <br><br>
            
            a effectué avec <span class="bold">SUCCÈS</span> un stage au sein de la structure 
            <span class="bold">{{ mb_strtoupper($structure->libelle, 'UTF-8') }}{{ $structure->sigle ? ' (' . mb_strtoupper($structure->sigle, 'UTF-8') . ')' : '' }}</span>
            du Ministère de l'Économie et des Finances,
            <br><br>
            
            du <span class="bold">{{ \Carbon\Carbon::parse($stage->date_debut)->isoFormat('dddd D MMMM YYYY') }}</span> 
            au <span class="bold">{{ \Carbon\Carbon::parse($stage->date_fin)->isoFormat('dddd D MMMM YYYY') }}</span>.
            <br><br>
            
            Ce stage a été <span class="bold">VALIDÉ</span> par la Direction de la Planification et de l'Administration Financière 
            conformément aux procédures en vigueur au Ministère de l'Économie et des Finances.
            <br><br>
            
            En foi de quoi la présente attestation lui est délivrée pour servir et valoir ce que de droit.
        </div>
        
        <div class="footer">
            Fait à Cotonou, le {{ $date_generation->format('d/m/Y') }}
        </div>
        
        <div class="signature">
            <strong>Le Directeur de la Planification <br>et de l'Administration Financière</strong><br><br>
            
            <div style="margin-top: 60px;">
                <span class="bold">{{ mb_strtoupper($agent_dpaf->user->nom ?? 'DIRECTEUR DPAF', 'UTF-8') }} {{ mb_strtoupper($agent_dpaf->user->prenom ?? '', 'UTF-8') }}</span>
            </div>
        </div>
    </div>
</body>
</html>
