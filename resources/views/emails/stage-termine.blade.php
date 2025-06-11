<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stage terminé</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; background: linear-gradient(135deg, #f5f8fa 0%, #e8f2f8 100%); color: #2c3e50; margin: 0; padding: 20px; line-height: 1.6;">
    <div style="max-width: 650px; margin: 20px auto; background: #ffffff; border-radius: 16px; box-shadow: 0 8px 32px rgba(0, 51, 102, 0.12); overflow: hidden; border: 1px solid rgba(52, 144, 220, 0.1);">
        
        <!-- Header Section -->
        <div style="background: linear-gradient(135deg, #059669 0%, #047857 100%); padding: 40px 24px; text-align: center; position: relative;">
            <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, #10b981, #34d399, #10b981);"></div>
            <img src="{{ asset('images/logoministere.png') }}" alt="Logo Ministère" style="height: 70px; margin-bottom: 16px; filter: brightness(1.1);">
            <h1 style="color: #ffffff; margin: 0; font-size: 1.75rem; font-weight: 600; letter-spacing: -0.5px;">Gestion des Stages</h1>
            <p style="color: rgba(255, 255, 255, 0.9); margin: 8px 0 0 0; font-size: 0.95rem;">Confirmation de fin de stage</p>
        </div>

        <!-- Main Content -->
        <div style="padding: 40px 32px;">
            
            <!-- Completion Message -->
            <div style="background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%); border: 2px solid #10b981; border-radius: 12px; padding: 24px; margin: 32px 0; text-align: center; position: relative;">
                <div style="position: absolute; top: -1px; left: 20px; background: #ffffff; padding: 0 12px; color: #10b981; font-weight: 600; font-size: 0.85rem;">✅ TERMINÉ</div>
                <div style="margin-top: 8px;">
                    <h2 style="color: #065f46; margin: 0 0 8px 0; font-size: 1.5rem; font-weight: 700;">Stage terminé avec succès !</h2>
                    <p style="margin: 0; color: #374151;">Votre stage a été marqué comme terminé par votre Maître de Stage.</p>
                </div>
            </div>

            <!-- Stage Title -->
            <div style="background: #fafbfc; border-radius: 12px; padding: 24px; margin: 32px 0; border: 1px solid #e5e7eb; text-align: center;">
                <h3 style="color: #1f2937; margin: 0 0 8px 0; font-size: 1.3rem; font-weight: 600;">📋 Stage terminé</h3>
                <p style="color: #10b981; font-size: 1.1rem; font-weight: 600; margin: 0; background: #f0fdf4; padding: 12px 20px; border-radius: 8px; display: inline-block;">
                    "{{ $stage->intitule }}"
                </p>
            </div>

            <!-- Stage Details -->
            <div style="background: #ffffff; border-radius: 12px; padding: 28px; margin: 32px 0; border: 1px solid #e5e7eb; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);">
                <h3 style="color: #003366; margin: 0 0 24px 0; font-size: 1.25rem; font-weight: 600; display: flex; align-items: center;">
                    📊 Détails du stage
                </h3>
                <div style="display: grid; gap: 16px;">
                    <div style="display: flex; align-items: flex-start; padding: 12px 0; border-bottom: 1px solid #f3f4f6;">
                        <span style="font-weight: 600; color: #374151; min-width: 160px; display: inline-block;">📌 Intitulé :</span>
                        <span style="color: #1f2937; font-size: 1rem;">{{ $stage->intitule }}</span>
                    </div>
                    <div style="display: flex; align-items: flex-start; padding: 12px 0; border-bottom: 1px solid #f3f4f6;">
                        <span style="font-weight: 600; color: #374151; min-width: 160px; display: inline-block;">📅 Date de début :</span>
                        <span style="color: #6b7280;">{{ $stage->date_debut->format('d/m/Y') }}</span>
                    </div>
                    <div style="display: flex; align-items: flex-start; padding: 12px 0; border-bottom: 1px solid #f3f4f6;">
                        <span style="font-weight: 600; color: #374151; min-width: 160px; display: inline-block;">📅 Date de fin :</span>
                        <span style="color: #6b7280;">{{ $stage->date_fin->format('d/m/Y') }}</span>
                    </div>
                    <div style="display: flex; align-items: flex-start; padding: 12px 0;">
                        <span style="font-weight: 600; color: #374151; min-width: 160px; display: inline-block;">✅ Date de confirmation :</span>
                        <span style="background: #f0fdf4; color: #065f46; padding: 4px 12px; border-radius: 20px; font-size: 0.9rem; font-weight: 600;">{{ $stage->date_confirmation_ms->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Congratulations Section -->
            <div style="background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); border-radius: 12px; padding: 24px; margin: 32px 0; border-left: 4px solid #f59e0b; text-align: center;">
                <h4 style="color: #92400e; margin: 0 0 16px 0; font-size: 1.2rem; font-weight: 600;">🎉 Félicitations !</h4>
                <p style="margin: 0; color: #78350f; line-height: 1.6; font-size: 1rem;">
                    Vous avez terminé votre stage avec succès. Nous espérons que cette expérience a été enrichissante et formatrice pour votre parcours professionnel.
                </p>
            </div>

            <!-- Next Steps -->
            <div style="background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); border-radius: 12px; padding: 24px; margin: 32px 0; border-left: 4px solid #0ea5e9;">
                <h4 style="color: #0c4a6e; margin: 0 0 16px 0; font-size: 1.1rem; font-weight: 600;">📝 Prochaines étapes</h4>
                <div style="color: #0e7490;">
                    <div style="margin-bottom: 12px; padding: 8px 0; border-bottom: 1px solid rgba(14, 165, 233, 0.2);">
                        <strong>1.</strong> Consultez votre rapport de stage si disponible
                    </div>
                    <div style="margin-bottom: 12px; padding: 8px 0; border-bottom: 1px solid rgba(14, 165, 233, 0.2);">
                        <strong>2.</strong> Récupérez vos documents et attestations
                    </div>
                    <div style="padding: 8px 0;">
                        <strong>3.</strong> Gardez contact avec votre structure d'accueil
                    </div>
                </div>
            </div>

            <!-- Action Button -->
            <div style="text-align: center; margin: 40px 0;">
                <a href="{{ route('stagiaire.stages.show', $stage->id) }}" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: #ffffff; padding: 16px 40px; text-decoration: none; border-radius: 10px; font-size: 1.1rem; font-weight: 600; display: inline-block; box-shadow: 0 4px 16px rgba(16, 185, 129, 0.3); transition: all 0.3s ease; border: none;">
                    📋 Voir les détails du stage
                </a>
            </div>

            <!-- Thank You Message -->
            <div style="background: #f8fafc; border-radius: 12px; padding: 24px; margin: 32px 0; border: 1px solid #e2e8f0; text-align: center;">
                <h4 style="color: #475569; margin: 0 0 12px 0; font-size: 1.1rem; font-weight: 600;">🙏 Merci pour votre engagement</h4>
                <p style="margin: 0; color: #64748b; line-height: 1.6;">
                    Nous vous remercions pour votre sérieux et votre investissement tout au long de ce stage. 
                    Nous vous souhaitons beaucoup de succès dans la suite de votre parcours !
                </p>
            </div>

            <!-- Signature -->
            <div style="margin-top: 40px; padding-top: 24px; border-top: 2px solid #f3f4f6;">
                <p style="margin: 0; color: #6b7280; font-size: 0.95rem; line-height: 1.5;">
                    Cordialement,<br>
                    <strong style="color: #374151;">{{ config('app.name') }}</strong><br>
                    <span style="font-size: 0.85rem;">Plateforme officielle de gestion des stages</span>
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div style="background: linear-gradient(135deg, #059669 0%, #047857 100%); color: #ffffff; text-align: center; padding: 24px 32px;">
            <p style="margin: 0; font-size: 0.85rem; opacity: 0.8;">
                © {{ date('Y') }} Gestion des Stages - République du Bénin. Tous droits réservés.
            </p>
        </div>
    </div>
</body>
</html>