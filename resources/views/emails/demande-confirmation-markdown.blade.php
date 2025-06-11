<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de votre demande de stage</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; background: linear-gradient(135deg, #f5f8fa 0%, #e8f2f8 100%); color: #2c3e50; margin: 0; padding: 20px; line-height: 1.6;">
    <div style="max-width: 650px; margin: 20px auto; background: #ffffff; border-radius: 16px; box-shadow: 0 8px 32px rgba(0, 51, 102, 0.12); overflow: hidden; border: 1px solid rgba(52, 144, 220, 0.1);">
        
        <!-- Header Section -->
        <div style="background: linear-gradient(135deg, #003366 0%, #1a4d7a 100%); padding: 40px 24px; text-align: center; position: relative;">
            <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, #3490dc, #60a5fa, #3490dc);"></div>
            <img src="{{ asset('images/logoministere.png') }}" alt="Logo Ministère" style="height: 70px; margin-bottom: 16px; filter: brightness(1.1);">
            <h1 style="color: #ffffff; margin: 0; font-size: 1.75rem; font-weight: 600; letter-spacing: -0.5px;">Gestion des Stages</h1>
            <p style="color: rgba(255, 255, 255, 0.9); margin: 8px 0 0 0; font-size: 0.95rem;">Plateforme officielle de gestion des demandes</p>
        </div>

        <!-- Main Content -->
        <div style="padding: 40px 32px;">
            
            <!-- Welcome Section -->
            <div style="border-left: 4px solid #3490dc; padding-left: 20px; margin-bottom: 32px;">
                <h2 style="color: #003366; margin: 0 0 16px 0; font-size: 1.5rem; font-weight: 600;">✓ Demande de stage confirmée</h2>
                <p style="margin: 0; font-size: 1.1rem; color: #4a5568;">Bonjour <strong style="color: #003366;">{{ $user->prenom }} {{ $user->nom }}</strong>,</p>
                <p style="margin: 12px 0 0 0; color: #6b7280;">Nous confirmons la réception de votre demande de stage. Votre dossier est actuellement <span style="color: #3490dc; font-weight: 600; background: #eef7ff; padding: 2px 8px; border-radius: 12px;">{{ $demande->statut }}</span>.</p>
            </div>

            <!-- Tracking Code Section -->
            <div style="background: linear-gradient(135deg, #eef7ff 0%, #f0f9ff 100%); border: 2px solid #3490dc; border-radius: 12px; padding: 24px; margin: 32px 0; text-align: center; position: relative;">
                <div style="position: absolute; top: -1px; left: 20px; background: #ffffff; padding: 0 12px; color: #3490dc; font-weight: 600; font-size: 0.85rem;">🔐 CODE DE SUIVI SÉCURISÉ</div>
                <div style="margin-top: 8px;">
                    <div style="font-size: 2rem; font-weight: 700; color: #003366; letter-spacing: 3px; margin-bottom: 8px; font-family: 'Courier New', monospace;">{{ $demande->code_suivi }}</div>
                    <p style="margin: 0; color: #6b7280; font-size: 0.9rem;">Conservez ce code précieusement pour suivre l'évolution de votre demande</p>
                </div>
            </div>

            <!-- Request Details Section -->
            <div style="background: #fafbfc; border-radius: 12px; padding: 28px; margin: 32px 0; border: 1px solid #e5e7eb;">
                <h3 style="color: #003366; margin: 0 0 20px 0; font-size: 1.25rem; font-weight: 600; display: flex; align-items: center;">
                    📋 Détails de votre demande
                </h3>
                <div style="display: grid; gap: 16px;">
                    <div style="display: flex; align-items: flex-start; padding: 12px 0; border-bottom: 1px solid #e5e7eb;">
                        <span style="font-weight: 600; color: #374151; min-width: 140px; display: inline-block;">🏢 Structure :</span>
                        <span style="color: #6b7280;">{{ $demande->structure ? $demande->structure->libelle : 'Non spécifiée' }}</span>
                    </div>
                    <div style="display: flex; align-items: flex-start; padding: 12px 0; border-bottom: 1px solid #e5e7eb;">
                        <span style="font-weight: 600; color: #374151; min-width: 140px; display: inline-block;">📂 Type :</span>
                        <span style="color: #6b7280;">{{ $demande->type }}</span>
                    </div>
                    <div style="display: flex; align-items: flex-start; padding: 12px 0; border-bottom: 1px solid #e5e7eb;">
                        <span style="font-weight: 600; color: #374151; min-width: 140px; display: inline-block;">⚙️ Nature :</span>
                        <span style="color: #6b7280;">{{ $demande->nature }}</span>
                    </div>
                    <div style="display: flex; align-items: flex-start; padding: 12px 0; border-bottom: 1px solid #e5e7eb;">
                        <span style="font-weight: 600; color: #374151; min-width: 140px; display: inline-block;">📅 Période :</span>
                        <span style="color: #6b7280;">Du {{ \Carbon\Carbon::parse($demande->date_debut)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($demande->date_fin)->format('d/m/Y') }}</span>
                    </div>
                    <div style="display: flex; align-items: flex-start; padding: 12px 0; border-bottom: 1px solid #e5e7eb;">
                        <span style="font-weight: 600; color: #374151; min-width: 140px; display: inline-block;">📊 Statut :</span>
                        <span style="background: #dcfce7; color: #166534; padding: 4px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">{{ $demande->statut }}</span>
                    </div>
                    <div style="display: flex; align-items: flex-start; padding: 12px 0;">
                        <span style="font-weight: 600; color: #374151; min-width: 140px; display: inline-block;">📝 Soumission :</span>
                        <span style="color: #6b7280;">{{ \Carbon\Carbon::parse($demande->date_soumission)->format('d/m/Y à H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- Next Steps Section -->
            <div style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border-radius: 12px; padding: 24px; margin: 32px 0; border-left: 4px solid #10b981;">
                <h4 style="color: #065f46; margin: 0 0 12px 0; font-size: 1.1rem; font-weight: 600;">🎯 Prochaines étapes</h4>
                <p style="margin: 0 0 16px 0; color: #374151;">Vous pouvez suivre l'évolution de votre demande en temps réel en vous connectant à votre espace personnel sur notre plateforme sécurisée.</p>
            </div>

            <!-- Action Button -->
            <div style="text-align: center; margin: 40px 0;">
                <a href="{{ $url }}" style="background: linear-gradient(135deg, #3490dc 0%, #2563eb 100%); color: #ffffff; padding: 16px 40px; text-decoration: none; border-radius: 10px; font-size: 1.1rem; font-weight: 600; display: inline-block; box-shadow: 0 4px 16px rgba(52, 144, 220, 0.3); transition: all 0.3s ease; border: none;">
                    🚀 Consulter mes demandes
                </a>
            </div>

            <!-- Support Section -->
            <div style="background: #fffbeb; border: 1px solid #fed7aa; border-radius: 12px; padding: 20px; margin: 32px 0;">
                <h4 style="color: #92400e; margin: 0 0 12px 0; font-size: 1rem; font-weight: 600;">💬 Besoin d'aide ?</h4>
                <p style="margin: 0; color: #78350f; font-size: 0.95rem;">Si vous avez des questions concernant votre demande ou si vous rencontrez des difficultés, notre équipe support est là pour vous accompagner.</p>
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
        <div style="background: linear-gradient(135deg, #003366 0%, #1a4d7a 100%); color: #ffffff; text-align: center; padding: 24px 32px;">
            <div style="border-bottom: 1px solid rgba(255, 255, 255, 0.2); padding-bottom: 16px; margin-bottom: 16px;">
                <p style="margin: 0; font-size: 0.9rem; opacity: 0.9;">
                    📧 support@stages.gov.bj | 📞 +229 XX XX XX XX | 🌐 www.stages.gov.bj
                </p>
            </div>
            <p style="margin: 0; font-size: 0.85rem; opacity: 0.8;">
                © {{ date('Y') }} Gestion des Stages - République du Bénin. Tous droits réservés.
            </p>
        </div>
    </div>

    <!-- Mobile Styles -->
    <style>
        @media only screen and (max-width: 600px) {
            body {
                padding: 10px !important;
            }
            div[style*="max-width: 650px"] {
                margin: 10px auto !important;
                border-radius: 12px !important;
            }
            div[style*="padding: 40px 32px"] {
                padding: 24px 20px !important;
            }
            div[style*="padding: 40px 24px"] {
                padding: 30px 20px !important;
            }
            h1 {
                font-size: 1.5rem !important;
            }
            h2 {
                font-size: 1.3rem !important;
            }
            div[style*="display: flex"] {
                display: block !important;
            }
            span[style*="min-width: 140px"] {
                display: block !important;
                margin-bottom: 4px !important;
                min-width: auto !important;
            }
        }
    </style>
</body>
</html>