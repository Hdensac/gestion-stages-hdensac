<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réponse à votre demande de stage</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; background: linear-gradient(135deg, #f5f8fa 0%, #e8f2f8 100%); color: #2c3e50; margin: 0; padding: 20px; line-height: 1.6;">
    <div style="max-width: 650px; margin: 20px auto; background: #ffffff; border-radius: 16px; box-shadow: 0 8px 32px rgba(0, 51, 102, 0.12); overflow: hidden; border: 1px solid rgba(220, 38, 38, 0.1);">
        
        <!-- Header Section -->
        <div style="background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); padding: 40px 24px; text-align: center; position: relative;">
            <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, #f87171, #fca5a5, #f87171);"></div>
            <img src="{{ asset('images/logoministere.png') }}" alt="Logo Ministère" style="height: 70px; margin-bottom: 16px; filter: brightness(1.1);">
            <h1 style="color: #ffffff; margin: 0; font-size: 1.75rem; font-weight: 600; letter-spacing: -0.5px;">Gestion des Stages</h1>
            <p style="color: rgba(255, 255, 255, 0.9); margin: 8px 0 0 0; font-size: 0.95rem;">Réponse à votre demande</p>
        </div>

        <!-- Main Content -->
        <div style="padding: 40px 32px;">
            <!-- Refusal Message -->
            <div style="border-left: 4px solid #dc2626; padding-left: 20px; margin-bottom: 32px;">
                <h2 style="color: #b91c1c; margin: 0 0 16px 0; font-size: 1.5rem; font-weight: 600;">
                    ❌ Demande de stage refusée
                </h2>
                <p style="margin: 0; font-size: 1.1rem; color: #4a5568;">
                    Bonjour <strong style="color: #b91c1c;">{{ $user->prenom }} {{ $user->nom }}</strong>,
                </p>
                <p style="margin: 12px 0 0 0; color: #6b7280;">
                    Nous sommes au regret de vous informer que votre demande de stage <span style="color: #dc2626; font-weight: 600; background: #fef2f2; padding: 2px 8px; border-radius: 12px;">{{ $demande->statut }}</span> n'a pas été acceptée.
                </p>
            </div>

            <!-- Motif de refus -->
            @if(isset($motif))
            <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; padding: 20px; margin: 32px 0;">
                <h4 style="color: #b91c1c; margin: 0 0 12px 0; font-size: 1.1rem; font-weight: 600;">Motif du refus :</h4>
                <p style="margin: 0; color: #991b1b; font-size: 1rem;">{{ $motif }}</p>
            </div>
            @endif

            <!-- Détails de la demande -->
            <div style="background: #fafbfc; border-radius: 12px; padding: 28px; margin: 32px 0; border: 1px solid #e5e7eb;">
                <h3 style="color: #b91c1c; margin: 0 0 20px 0; font-size: 1.25rem; font-weight: 600; display: flex; align-items: center;">
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
                        <span style="background: #fee2e2; color: #b91c1c; padding: 4px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">{{ $demande->statut }}</span>
                    </div>
                    <div style="display: flex; align-items: flex-start; padding: 12px 0;">
                        <span style="font-weight: 600; color: #374151; min-width: 140px; display: inline-block;">📝 Soumission :</span>
                        <span style="color: #6b7280;">{{ \Carbon\Carbon::parse($demande->date_soumission)->format('d/m/Y à H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- Conseils ou relance -->
            <div style="background: #fef9c3; border: 1px solid #fde68a; border-radius: 12px; padding: 20px; margin: 32px 0;">
                <h4 style="color: #b45309; margin: 0 0 12px 0; font-size: 1rem; font-weight: 600;">💡 Conseil</h4>
                <p style="margin: 0; color: #92400e; font-size: 0.95rem;">
                    N'hésitez pas à consulter régulièrement la plateforme pour d'autres opportunités de stage ou à contacter notre équipe pour toute question.
                </p>
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
        <div style="background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); color: #ffffff; text-align: center; padding: 24px 32px;">
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