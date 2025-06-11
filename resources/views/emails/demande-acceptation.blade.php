<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceptation de votre demande de stage</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; background: linear-gradient(135deg, #f5f8fa 0%, #e8f2f8 100%); color: #2c3e50; margin: 0; padding: 20px; line-height: 1.6;">
    <div style="max-width: 650px; margin: 20px auto; background: #ffffff; border-radius: 16px; box-shadow: 0 8px 32px rgba(0, 51, 102, 0.12); overflow: hidden; border: 1px solid rgba(52, 144, 220, 0.1);">
        
        <!-- Header Section -->
        <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); padding: 40px 24px; text-align: center; position: relative;">
            <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, #34d399, #6ee7b7, #34d399);"></div>
            <img src="{{ asset('images/logoministere.png') }}" alt="Logo Ministère" style="height: 70px; margin-bottom: 16px; filter: brightness(1.1);">
            <h1 style="color: #ffffff; margin: 0; font-size: 1.75rem; font-weight: 600; letter-spacing: -0.5px;">Gestion des Stages</h1>
            <p style="color: rgba(255, 255, 255, 0.9); margin: 8px 0 0 0; font-size: 0.95rem;">Demande acceptée avec succès</p>
        </div>

        <!-- Main Content -->
        <div style="padding: 40px 32px;">
            
            <!-- Success Message -->
            <div style="background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%); border: 2px solid #10b981; border-radius: 12px; padding: 24px; margin: 32px 0; text-align: center; position: relative;">
                <div style="position: absolute; top: -1px; left: 20px; background: #ffffff; padding: 0 12px; color: #10b981; font-weight: 600; font-size: 0.85rem;">🎉 FÉLICITATIONS</div>
                <div style="margin-top: 8px;">
                    <h2 style="color: #065f46; margin: 0 0 8px 0; font-size: 1.5rem; font-weight: 700;">Votre demande de stage a été acceptée !</h2>
                    <p style="margin: 0; color: #374151;">Bonjour <strong style="color: #10b981;">{{ $nomUser }}</strong>, nous avons le plaisir de vous confirmer l'acceptation de votre demande.</p>
                </div>
            </div>

            <!-- Stage Details -->
            <div style="background: #fafbfc; border-radius: 12px; padding: 28px; margin: 32px 0; border: 1px solid #e5e7eb;">
                <h3 style="color: #003366; margin: 0 0 20px 0; font-size: 1.25rem; font-weight: 600; display: flex; align-items: center;">
                    📋 Détails de votre stage
                </h3>
                <div style="display: grid; gap: 16px;">
                    <div style="display: flex; align-items: flex-start; padding: 12px 0; border-bottom: 1px solid #e5e7eb;">
                        <span style="font-weight: 600; color: #374151; min-width: 140px; display: inline-block;">🏢 Structure :</span>
                        <span style="color: #6b7280;">{{ $structure }}</span>
                    </div>
                    <div style="display: flex; align-items: flex-start; padding: 12px 0; border-bottom: 1px solid #e5e7eb;">
                        <span style="font-weight: 600; color: #374151; min-width: 140px; display: inline-block;">📅 Début :</span>
                        <span style="color: #6b7280;">{{ $dateDebut }}</span>
                    </div>
                    <div style="display: flex; align-items: flex-start; padding: 12px 0; border-bottom: 1px solid #e5e7eb;">
                        <span style="font-weight: 600; color: #374151; min-width: 140px; display: inline-block;">📅 Fin :</span>
                        <span style="color: #6b7280;">{{ $dateFin }}</span>
                    </div>
                    <div style="display: flex; align-items: flex-start; padding: 12px 0;">
                        <span style="font-weight: 600; color: #374151; min-width: 140px; display: inline-block;">🔐 Code suivi :</span>
                        <span style="background: #eef7ff; color: #1e40af; padding: 4px 12px; border-radius: 20px; font-size: 0.9rem; font-weight: 600; font-family: monospace;">{{ $codeSuivi }}</span>
                    </div>
                </div>
            </div>

            <!-- Next Steps -->
            <div style="background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); border-radius: 12px; padding: 24px; margin: 32px 0; border-left: 4px solid #f59e0b;">
                <h4 style="color: #92400e; margin: 0 0 16px 0; font-size: 1.1rem; font-weight: 600;">🎯 Prochaines étapes importantes</h4>
                <div style="color: #78350f;">
                    <div style="margin-bottom: 12px; padding: 8px 0; border-bottom: 1px solid rgba(245, 158, 11, 0.2);">
                        <strong>1.</strong> Prenez connaissance des documents nécessaires pour votre stage
                    </div>
                    <div style="margin-bottom: 12px; padding: 8px 0; border-bottom: 1px solid rgba(245, 158, 11, 0.2);">
                        <strong>2.</strong> Complétez et signez la convention de stage
                    </div>
                    <div style="padding: 8px 0;">
                        <strong>3.</strong> Contactez votre structure d'accueil pour les modalités pratiques
                    </div>
                </div>
            </div>

            <!-- Action Button -->
            <div style="text-align: center; margin: 40px 0;">
                <a href="{{ $url }}" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: #ffffff; padding: 16px 40px; text-decoration: none; border-radius: 10px; font-size: 1.1rem; font-weight: 600; display: inline-block; box-shadow: 0 4px 16px rgba(16, 185, 129, 0.3); transition: all 0.3s ease; border: none;">
                    🚀 Accéder à mon espace
                </a>
            </div>

            <!-- Congratulations -->
            <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 20px; margin: 32px 0; text-align: center;">
                <h4 style="color: #065f46; margin: 0 0 8px 0; font-size: 1.1rem; font-weight: 600;">🎊 Félicitations !</h4>
                <p style="margin: 0; color: #166534; font-size: 0.95rem;">Nous vous souhaitons un excellent stage riche en apprentissages et en expériences !</p>
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
        <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: #ffffff; text-align: center; padding: 24px 32px;">
            <p style="margin: 0; font-size: 0.85rem; opacity: 0.8;">
                © {{ date('Y') }} Gestion des Stages - République du Bénin. Tous droits réservés.
            </p>
        </div>
    </div>
</body>
</html>
