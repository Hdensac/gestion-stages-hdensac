<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attestation d'effectivité de stage disponible</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8fafc;
        }
        .container {
            background: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #059669;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #059669;
            margin-bottom: 10px;
        }
        .title {
            font-size: 28px;
            font-weight: bold;
            color: #1f2937;
            margin: 20px 0;
            text-align: center;
        }
        .greeting {
            font-size: 18px;
            color: #374151;
            margin-bottom: 20px;
        }
        .content {
            font-size: 16px;
            line-height: 1.8;
            color: #4b5563;
        }
        .highlight-box {
            background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
            border-left: 4px solid #0ea5e9;
            padding: 20px;
            margin: 25px 0;
            border-radius: 8px;
        }
        .stage-details {
            background: #f8fafc;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .stage-details h3 {
            color: #059669;
            margin-top: 0;
            font-size: 18px;
        }
        .detail-item {
            display: flex;
            margin: 10px 0;
            align-items: center;
        }
        .detail-label {
            font-weight: bold;
            color: #374151;
            min-width: 140px;
        }
        .detail-value {
            color: #059669;
            font-weight: 600;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #059669, #10b981);
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            font-size: 16px;
            margin: 25px 0;
            text-align: center;
            box-shadow: 0 4px 6px rgba(5, 150, 105, 0.3);
            transition: transform 0.2s;
        }
        .cta-button:hover {
            transform: translateY(-2px);
        }
        .important-note {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }
        .important-note strong {
            color: #92400e;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
        }
        .signature {
            margin-top: 30px;
            font-style: italic;
            color: #374151;
        }
        .ministry-info {
            background: #1f2937;
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
            text-align: center;
        }
        .success-badge {
            background: #10b981;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
            display: inline-block;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">🎓 MINISTÈRE DE L'ÉCONOMIE ET DES FINANCES</div>
            <div style="color: #6b7280; font-size: 14px;">Gestion des Stages</div>
        </div>

        <div class="title">
            📄 Votre attestation d'effectivité de  stage est disponible !
        </div>

        <div class="greeting">
            Bonjour <strong>{{ $stagiaire->user->prenom ?? '' }} {{ $stagiaire->user->nom ?? '' }}</strong>,
        </div>

        <div class="content">
            <p>Nous avons le plaisir de vous informer que votre <strong>attestation  d'effectivité de stage</strong> est maintenant disponible et prête à être téléchargée.</p>
            
            <div class="success-badge">✅ Stage terminé avec succès</div>
        </div>

        <div class="stage-details">
            <h3>📋 Détails de votre stage</h3>
            <div class="detail-item">
                <span class="detail-label">Structure d'accueil :</span>
                <span class="detail-value">{{ $structure->libelle ?? 'Non spécifié' }}{{ $structure && $structure->sigle ? ' (' . $structure->sigle . ')' : '' }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Période :</span>
                <span class="detail-value">
                    du {{ $stage ? \Carbon\Carbon::parse($stage->date_debut)->format('d/m/Y') : '' }} 
                    au {{ $stage ? \Carbon\Carbon::parse($stage->date_fin)->format('d/m/Y') : '' }}
                </span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Durée :</span>
                <span class="detail-value">
                    {{ $stage ? \Carbon\Carbon::parse($stage->date_debut)->diffInDays(\Carbon\Carbon::parse($stage->date_fin)) + 1 : '' }} jours
                </span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Date de génération :</span>
                <span class="detail-value">{{ now()->format('d/m/Y à H:i') }}</span>
            </div>
        </div>

        <div class="highlight-box">
            <p><strong>🏛️ Certification officielle</strong></p>
            <p>Cette attestation certifie que vous avez effectué votre stage dans les conditions requises au sein du <strong>Ministère de l'Économie et des Finances</strong>.</p>
        </div>

        <div style="text-align: center;">
            <a href="{{ url('/stagiaire/dashboard') }}" class="cta-button">
                📄 Accéder à mon espace stagiaire
            </a>
        </div>

        <div class="important-note">
            <strong>⚠️ Important :</strong> Cette attestation d'effectivité  constitue la première étape de validation. Une attestation finale sera délivrée par la <strong>DPAF (Direction de la Programmation et de l'Administration Financière)</strong> après validation complète de votre dossier.
        </div>

        <div class="content">
            <p>Pour toute question concernant votre attestation, n'hésitez pas à contacter votre responsable de structure.</p>
            
            <p><strong>🎉 Félicitations pour la réussite de votre stage !</strong></p>
        </div>

        <div class="signature">
            <p>Cordialement,</p>
            <p><strong>L'équipe de gestion des stages</strong></p>
        </div>

        <div class="ministry-info">
            <strong>MINISTÈRE DE L'ÉCONOMIE ET DES FINANCES</strong><br>
            <small>République du Bénin - Fraternité, Justice, Travail</small>
        </div>

        <div class="footer">
            <p>Cet email a été envoyé automatiquement. Merci de ne pas y répondre.</p>
            <p>© {{ date('Y') }} Ministère de l'Économie et des Finances - Tous droits réservés</p>
        </div>
    </div>
</body>
</html>
