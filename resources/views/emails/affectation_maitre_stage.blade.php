<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Affectation de votre maître de stage</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; background: linear-gradient(135deg, #f5f8fa 0%, #e8f2f8 100%); color: #2c3e50; margin: 0; padding: 20px; line-height: 1.6;">
    <div style="max-width: 650px; margin: 20px auto; background: #ffffff; border-radius: 16px; box-shadow: 0 8px 32px rgba(0, 51, 102, 0.12); overflow: hidden; border: 1px solid rgba(52, 144, 220, 0.1);">
        
        <!-- Header Section -->
        <div style="background: linear-gradient(135deg, #003366 0%, #1a4d7a 100%); padding: 40px 24px; text-align: center; position: relative;">
            <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, #3490dc, #60a5fa, #3490dc);"></div>
            <img src="{{ asset('images/logoministere.png') }}" alt="Logo Ministère" style="height: 70px; margin-bottom: 16px; filter: brightness(1.1);">
            <h1 style="color: #ffffff; margin: 0; font-size: 1.75rem; font-weight: 600; letter-spacing: -0.5px;">Gestion des Stages</h1>
            <p style="color: rgba(255, 255, 255, 0.9); margin: 8px 0 0 0; font-size: 0.95rem;">Affectation de maître de stage</p>
        </div>

        <!-- Main Content -->
        <div style="padding: 40px 32px;">
            
            <!-- Welcome Section -->
            <div style="border-left: 4px solid #10b981; padding-left: 20px; margin-bottom: 32px;">
                @if($isReaffectation)
                <h2 style="color: #003366; margin: 0 0 16px 0; font-size: 1.5rem; font-weight: 600;">🔄 Réaffectation de maître de stage</h2>
                <p style="margin: 0; font-size: 1.1rem; color: #4a5568;">Bonjour <strong style="color: #003366;">{{ $stagiaireNom }}</strong>,</p>
                <p style="margin: 12px 0 0 0; color: #6b7280;">Nous vous informons que votre maître de stage pour le stage <strong style="color: #003366; background: #eef7ff; padding: 2px 8px; border-radius: 12px;">{{ $stage->themeStage->intitule ?? 'sans intitulé' }}</strong> a été <span style="color: #f59e0b; font-weight: 600;">réaffecté</span>.</p>
                @else
                <h2 style="color: #003366; margin: 0 0 16px 0; font-size: 1.5rem; font-weight: 600;">👨‍🏫 Affectation de maître de stage</h2>
                <p style="margin: 0; font-size: 1.1rem; color: #4a5568;">Bonjour <strong style="color: #003366;">{{ $stagiaireNom }}</strong>,</p>
                <p style="margin: 12px 0 0 0; color: #6b7280;">Nous vous informons que vous avez été affecté à un maître de stage pour le stage <strong style="color: #003366; background: #eef7ff; padding: 2px 8px; border-radius: 12px;">{{ $stage->themeStage->intitule ?? 'sans intitulé' }}</strong>.</p>
                @endif
            </div>

            <!-- Maitre de Stage Info -->
            <div style="background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%); border: 2px solid #10b981; border-radius: 12px; padding: 24px; margin: 32px 0; position: relative;">
                <div style="position: absolute; top: -1px; left: 20px; background: #ffffff; padding: 0 12px; color: #10b981; font-weight: 600; font-size: 0.85rem;">👨‍💼 VOTRE MAÎTRE DE STAGE</div>
                <div style="margin-top: 8px;">
                    <div style="display: grid; gap: 16px;">
                        <div style="display: flex; align-items: center; padding: 12px 0; border-bottom: 1px solid rgba(16, 185, 129, 0.2);">
                            <span style="font-weight: 600; color: #065f46; min-width: 80px; display: inline-block;">👤 Nom :</span>
                            <span style="color: #374151; font-size: 1.1rem; font-weight: 600;">{{ $msNom }}</span>
                        </div>
                        <div style="display: flex; align-items: center; padding: 12px 0;">
                            <span style="font-weight: 600; color: #065f46; min-width: 80px; display: inline-block;">📧 Email :</span>
                            <span style="color: #3490dc; font-weight: 500;">{{ $maitreStage->email }}</span>
                        </div>
                    </div>
                </div>
            </div>

            @if($isReaffectation)
            <!-- Date Effect -->
            <div style="background: #fef3c7; border: 1px solid #fcd34d; border-radius: 12px; padding: 20px; margin: 32px 0;">
                <h4 style="color: #92400e; margin: 0 0 12px 0; font-size: 1rem; font-weight: 600;">📅 Date d'effet</h4>
                <p style="margin: 0; color: #78350f; font-size: 0.95rem;">Cette modification prend effet à partir du <strong>{{ \Carbon\Carbon::parse($stage->date_debut)->format('d/m/Y') }}</strong>.</p>
            </div>
            @endif

            <!-- Contact Info -->
            <div style="background: #eef7ff; border-radius: 12px; padding: 24px; margin: 32px 0; border-left: 4px solid #3490dc;">
                <h4 style="color: #1e40af; margin: 0 0 12px 0; font-size: 1.1rem; font-weight: 600;">💬 Communication</h4>
                <p style="margin: 0; color: #374151;">Pour toute question concernant votre stage, vous pouvez contacter directement votre maître de stage à l'adresse email indiquée ci-dessus.</p>
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
            <p style="margin: 0; font-size: 0.85rem; opacity: 0.8;">
                © {{ date('Y') }} Gestion des Stages - République du Bénin. Tous droits réservés.
            </p>
        </div>
    </div>
</body>
</html>


