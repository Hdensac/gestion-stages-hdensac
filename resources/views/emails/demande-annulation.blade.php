<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Annulation de votre demande de stage</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; background: linear-gradient(135deg, #f5f8fa 0%, #e8f2f8 100%); color: #2c3e50; margin: 0; padding: 20px; line-height: 1.6;">
    <div style="max-width: 650px; margin: 20px auto; background: #ffffff; border-radius: 16px; box-shadow: 0 8px 32px rgba(0, 51, 102, 0.12); overflow: hidden; border: 1px solid rgba(52, 144, 220, 0.1);">
        
        <!-- Header Section -->
        <div style="background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%); padding: 40px 24px; text-align: center; position: relative;">
            <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, #9ca3af, #d1d5db, #9ca3af);"></div>
            <img src="{{ asset('images/logoministere.png') }}" alt="Logo Ministère" style="height: 70px; margin-bottom: 16px; filter: brightness(1.1);">
            <h1 style="color: #ffffff; margin: 0; font-size: 1.75rem; font-weight: 600; letter-spacing: -0.5px;">Gestion des Stages</h1>
            <p style="color: rgba(255, 255, 255, 0.9); margin: 8px 0 0 0; font-size: 0.95rem;">Annulation de demande</p>
        </div>

        <!-- Main Content -->
        <div style="padding: 40px 32px;">
            
            <!-- Cancellation Message -->
            <div style="background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%); border: 2px solid #f87171; border-radius: 12px; padding: 24px; margin: 32px 0; text-align: center; position: relative;">
                <div style="position: absolute; top: -1px; left: 20px; background: #ffffff; padding: 0 12px; color: #dc2626; font-weight: 600; font-size: 0.85rem;">ℹ️ INFORMATION</div>
                <div style="margin-top: 8px;">
                    <h2 style="color: #991b1b; margin: 0 0 8px 0; font-size: 1.5rem; font-weight: 700;">Demande de stage annulée</h2>
                    <p style="margin: 0; color: #374151;">Bonjour <strong style="color: #dc2626;">{{ $nomUser }}</strong>, nous confirmons l'annulation de votre demande de stage.</p>
                </div>
            </div>

            <!-- Tracking Code -->
            <div style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border: 2px solid #6b7280; border-radius: 12px; padding: 24px; margin: 32px 0; text-align: center; position: relative;">
                <div style="position: absolute; top: -1px; left: 20px; background: #ffffff; padding: 0 12px; color: #6b7280; font-weight: 600; font-size: 0.85rem;">🔐 CODE DE SUIVI ANNULÉ</div>
                <div style="margin-top: 8px;">
                    <div style="font-size: 2rem; font-weight: 700; color: #374151; letter-spacing: 3px; margin-bottom: 8px; font-family: 'Courier New', monospace; text-decoration: line-through;">{{ $codeSuivi }}</div>
                    <p style="margin: 0; color: #6b7280; font-size: 0.9rem;">Ce code de suivi n'est plus valide</p>
                </div>
            </div>

            <!-- Request Details -->
            <div style="background: #fafbfc; border-radius: 12px; padding: 28px; margin: 32px 0; border: 1px solid #e5e7eb;">
                <h3 style="color: #003366; margin: 0 0 20px 0; font-size: 1.25rem; font-weight: 600; display: flex; align-items: center;">
                    📋 Détails de la demande annulée
                </h3>
                <div style="display: grid; gap: 16px;">
                    <div style="display: flex; align-items: flex-start; padding: 12px 0; border-bottom: 1px solid #e5e7eb;">
                        <span style="font-weight: 600; color: #374151; min-width: 160px; display: inline-block;">🏢 Structure :</span>
                        <span style="color: #6b7280;">{{ $structure ?? 'Non spécifiée' }}</span>
                    </div>
                    <div style="display: flex; align-items: flex-start; padding: 12px 0; border-bottom: 1px solid #e5e7eb;">
                        <span style="font-weight: 600; color: #374151; min-width: 160px; display: inline-block;">📅 Date demande :</span>
                        <span style="color: #6b7280;">{{ $dateDemande }}</span>
                    </div>
                    <div style="display: flex; align-items: flex-start; padding: 12px 0;">
                        <span style="font-weight: 600; color: #374151; min-width: 160px; display: inline-block;">🗑️ Date annulation :</span>
                        <span style="color: #6b7280;">{{ $dateAnnulation }}</span>
                    </div>
                </div>
            </div>

            <!-- New Request Section -->
            <div style="background: linear-gradient(135deg, #eef7ff 0%, #dbeafe 100%); border-radius: 12px; padding: 24px; margin: 32px 0; border-left: 4px solid #3490dc;">
                <h4 style="color: #1e40af; margin: 0 0 12px 0; font-size: 1.1rem; font-weight: 600;">🔄 Nouvelle demande</h4>
                <p style="margin: 0 0 16px 0; color: #374151;">Si vous souhaitez soumettre une nouvelle demande, vous pouvez le faire à tout moment depuis votre espace personnel.</p>
            </div>

            <!-- Action Button -->
            <div style="text-align: center; margin: 40px 0;">
                <a href="{{ $url }}" style="background: linear-gradient(135deg, #3490dc 0%, #2563eb 100%); color: #ffffff; padding: 16px 40px; text-decoration: none; border-radius: 10px; font-size: 1.1rem; font-weight: 600; display: inline-block; box-shadow: 0 4px 16px rgba(52, 144, 220, 0.3); transition: all 0.3s ease; border: none;">
                    🚀 Accéder à mon espace
                </a>
            </div>

            <!-- Security Warning -->
            <div style="background: #fffbeb; border: 1px solid #fed7aa; border-radius: 12px; padding: 20px; margin: 32px 0;">
                <h4 style="color: #92400e; margin: 0 0 12px 0; font-size: 1rem; font-weight: 600;">⚠️ Sécurité</h4>
                <p style="margin: 0; color: #78350f; font-size: 0.95rem;">Si vous n'êtes pas à l'origine de cette annulation, veuillez nous contacter immédiatement via votre espace personnel.</p>
            </div>

            <!-- Signature -->
            <div style="margin-top: 40px; padding-top: 24px; border-top: 2px solid #f3f4f6;">
                <p style="margin: 0; color: #6b7280; font-size: 0.95rem; line-height: 1.5;">
                    Cordialement,<br>
                    <strong style="color: #374151;">L'équipe Gestion des Stages</strong><br>
                    <span style="font-size: 0.85rem;">Plateforme officielle de gestion des demandes de stage</span>
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div style="background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%); color: #ffffff; text-align: center; padding: 24px 32px;">
            <p style="margin: 0; font-size: 0.85rem; opacity: 0.8;">
                © {{ date('Y') }} Gestion des Stages - République du Bénin. Tous droits réservés.
            </p>
        </div>
    </div>
</body>
</html>