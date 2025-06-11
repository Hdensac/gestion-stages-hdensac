<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $data['subject'] }}</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; background: linear-gradient(135deg, #f5f8fa 0%, #e8f2f8 100%); color: #2c3e50; margin: 0; padding: 20px; line-height: 1.6;">
    <div style="max-width: 650px; margin: 20px auto; background: #ffffff; border-radius: 16px; box-shadow: 0 8px 32px rgba(0, 51, 102, 0.12); overflow: hidden; border: 1px solid rgba(52, 144, 220, 0.1);">
        
        <!-- Header Section -->
        <div style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); padding: 40px 24px; text-align: center; position: relative;">
            <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, #60a5fa, #93c5fd, #60a5fa);"></div>
            <img src="{{ asset('images/logoministere.png') }}" alt="Logo Ministère" style="height: 70px; margin-bottom: 16px; filter: brightness(1.1);">
            <h1 style="color: #ffffff; margin: 0; font-size: 1.75rem; font-weight: 600; letter-spacing: -0.5px;">Gestion des Stages</h1>
            <p style="color: rgba(255, 255, 255, 0.9); margin: 8px 0 0 0; font-size: 0.95rem;">Message de votre maître de stage</p>
        </div>

        <!-- Main Content -->
        <div style="padding: 40px 32px;">
            
            <!-- Subject Header -->
            <div style="background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); border: 2px solid #3b82f6; border-radius: 12px; padding: 24px; margin: 32px 0; text-align: center; position: relative;">
                <div style="position: absolute; top: -1px; left: 20px; background: #ffffff; padding: 0 12px; color: #3b82f6; font-weight: 600; font-size: 0.85rem;">📧 MESSAGE</div>
                <div style="margin-top: 8px;">
                    <h2 style="color: #1e40af; margin: 0 0 8px 0; font-size: 1.5rem; font-weight: 700;">{{ $data['subject'] }}</h2>
                    <p style="margin: 0; color: #374151;">Message de <strong style="color: #3b82f6;">{{ $data['agent']->prenom }} {{ $data['agent']->nom }}</strong></p>
                </div>
            </div>

            <!-- Stage Details -->
            <div style="background: #fafbfc; border-radius: 12px; padding: 28px; margin: 32px 0; border: 1px solid #e5e7eb;">
                <h3 style="color: #003366; margin: 0 0 20px 0; font-size: 1.25rem; font-weight: 600; display: flex; align-items: center;">
                    🏢 Informations du stage
                </h3>
                <div style="display: grid; gap: 16px;">
                    <div style="display: flex; align-items: flex-start; padding: 12px 0; border-bottom: 1px solid #e5e7eb;">
                        <span style="font-weight: 600; color: #374151; min-width: 140px; display: inline-block;">🏢 Structure :</span>
                        <span style="color: #6b7280;">{{ $data['stage']->structure->libelle ?? 'Structure non spécifiée' }}</span>
                    </div>
                    <div style="display: flex; align-items: flex-start; padding: 12px 0;">
                        <span style="font-weight: 600; color: #374151; min-width: 140px; display: inline-block;">👨‍💼 Maître de stage :</span>
                        <span style="color: #6b7280;">{{ $data['agent']->prenom }} {{ $data['agent']->nom }}</span>
                    </div>
                </div>
            </div>

            <!-- Message Content -->
            <div style="background: #ffffff; border-radius: 12px; padding: 28px; margin: 32px 0; border: 1px solid #e5e7eb; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);">
                <h4 style="color: #1f2937; margin: 0 0 20px 0; font-size: 1.1rem; font-weight: 600; display: flex; align-items: center;">
                    💬 Message
                </h4>
                <div style="color: #374151; font-size: 1rem; line-height: 1.7; white-space: pre-line;">
                    {!! nl2br(e($data['message'])) !!}
                </div>
            </div>

            <!-- Important Notice -->
            <div style="background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); border-radius: 12px; padding: 24px; margin: 32px 0; border-left: 4px solid #f59e0b;">
                <h4 style="color: #92400e; margin: 0 0 16px 0; font-size: 1.1rem; font-weight: 600;">⚠️ Important à retenir</h4>
                <div style="color: #78350f;">
                    <div style="margin-bottom: 12px; padding: 8px 0; border-bottom: 1px solid rgba(245, 158, 11, 0.2);">
                        <strong>•</strong> Ce message concerne votre stage en cours
                    </div>
                    <div style="margin-bottom: 12px; padding: 8px 0; border-bottom: 1px solid rgba(245, 158, 11, 0.2);">
                        <strong>•</strong> Ne répondez pas directement à cet email
                    </div>
                    <div style="padding: 8px 0;">
                        <strong>•</strong> Utilisez la plateforme pour toute communication
                    </div>
                </div>
            </div>

            <!-- Signature Section -->
            <div style="background: #f8fafc; border-radius: 12px; padding: 24px; margin: 32px 0; border: 1px solid #e2e8f0;">
                <h4 style="color: #475569; margin: 0 0 16px 0; font-size: 1rem; font-weight: 600;">✍️ Signature</h4>
                <div style="color: #64748b; font-style: italic; line-height: 1.6;">
                    <p style="margin: 0 0 8px 0;">Cordialement,</p>
                    <p style="margin: 0; font-weight: 600; color: #1e293b;">{{ $data['agent']->prenom }} {{ $data['agent']->nom }}</p>
                    <p style="margin: 4px 0 0 0; font-size: 0.9rem;">Maître de Stage</p>
                    <p style="margin: 4px 0 0 0; font-size: 0.9rem; color: #3b82f6;">{{ $data['stage']->structure->libelle ?? 'Structure non spécifiée' }}</p>
                </div>
            </div>

            <!-- Contact Info -->
            <div style="background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%); border: 1px solid #bbf7d0; border-radius: 12px; padding: 20px; margin: 32px 0; text-align: center;">
                <h4 style="color: #065f46; margin: 0 0 8px 0; font-size: 1.1rem; font-weight: 600;">📞 Besoin d'aide ?</h4>
                <p style="margin: 0; color: #166534; font-size: 0.95rem;">Pour toute question, contactez votre maître de stage via la plateforme de gestion des stages.</p>
            </div>
        </div>

        <!-- Footer -->
        <div style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: #ffffff; text-align: center; padding: 24px 32px;">
            <p style="margin: 0; font-size: 0.85rem; opacity: 0.8;">
                © {{ date('Y') }} Gestion des Stages - République du Bénin. Tous droits réservés.
            </p>
        </div>
    </div>
</body>
</html>