<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification de thème de stage</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; background: linear-gradient(135deg, #f5f8fa 0%, #e8f2f8 100%); color: #2c3e50; margin: 0; padding: 20px; line-height: 1.6;">
    <div style="max-width: 650px; margin: 20px auto; background: #ffffff; border-radius: 16px; box-shadow: 0 8px 32px rgba(0, 51, 102, 0.12); overflow: hidden; border: 1px solid rgba(52, 144, 220, 0.1);">
        
        <!-- Header Section -->
        <div style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); padding: 40px 24px; text-align: center; position: relative;">
            <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, #a78bfa, #c4b5fd, #a78bfa);"></div>
            <img src="{{ asset('images/logoministere.png') }}" alt="Logo Ministère" style="height: 70px; margin-bottom: 16px; filter: brightness(1.1);">
            <h1 style="color: #ffffff; margin: 0; font-size: 1.75rem; font-weight: 600; letter-spacing: -0.5px;">Gestion des Stages</h1>
            <p style="color: rgba(255, 255, 255, 0.9); margin: 8px 0 0 0; font-size: 0.95rem;">Notification de thème de stage</p>
        </div>

        <!-- Main Content -->
        <div style="padding: 40px 32px;">
            
            <!-- Greeting -->
            <div style="margin-bottom: 32px;">
                <h2 style="color: #1f2937; margin: 0 0 8px 0; font-size: 1.5rem; font-weight: 600;">
                    Bonjour {{ $stagiaire->prenom }} {{ $stagiaire->nom }},
                </h2>
                <p style="margin: 0; color: #6b7280; font-size: 1rem;">
                    Nous avons une mise à jour concernant votre stage.
                </p>
            </div>

            <!-- Theme Status -->
            @if($theme->etat === 'Modifié')
            <div style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border: 2px solid #f59e0b; border-radius: 12px; padding: 24px; margin: 32px 0; text-align: center; position: relative;">
                <div style="position: absolute; top: -1px; left: 20px; background: #ffffff; padding: 0 12px; color: #f59e0b; font-weight: 600; font-size: 0.85rem;">🔄 MODIFICATION</div>
                <div style="margin-top: 8px;">
                    <h2 style="color: #92400e; margin: 0 0 8px 0; font-size: 1.4rem; font-weight: 700;">Thème de stage modifié</h2>
                    <p style="margin: 0; color: #78350f;">Votre maître de stage a apporté des modifications au thème de votre stage.</p>
                </div>
            </div>
            @else
            <div style="background: linear-gradient(135deg, #ddd6fe 0%, #c4b5fd 100%); border: 2px solid #8b5cf6; border-radius: 12px; padding: 24px; margin: 32px 0; text-align: center; position: relative;">
                <div style="position: absolute; top: -1px; left: 20px; background: #ffffff; padding: 0 12px; color: #8b5cf6; font-weight: 600; font-size: 0.85rem;">📋 NOUVEAU THÈME</div>
                <div style="margin-top: 8px;">
                    <h2 style="color: #5b21b6; margin: 0 0 8px 0; font-size: 1.4rem; font-weight: 700;">Proposition de thème</h2>
                    <p style="margin: 0; color: #6d28d9;">Votre maître de stage vient de proposer un thème pour votre stage.</p>
                </div>
            </div>
            @endif

            <!-- Stage Info -->
            <div style="background: #fafbfc; border-radius: 12px; padding: 28px; margin: 32px 0; border: 1px solid #e5e7eb;">
                <h3 style="color: #003366; margin: 0 0 20px 0; font-size: 1.25rem; font-weight: 600; display: flex; align-items: center;">
                    🏢 Structure d'accueil
                </h3>
                <div style="display: flex; align-items: center; padding: 12px 0;">
                    <span style="font-weight: 600; color: #374151; min-width: 120px; display: inline-block;">Structure :</span>
                    <span style="color: #6b7280; font-size: 1.1rem;">{{ $stage->structure->libelle ?? 'Structure d\'accueil' }}</span>
                </div>
            </div>

            <!-- Theme Details -->
            <div style="background: #ffffff; border-radius: 12px; padding: 28px; margin: 32px 0; border: 1px solid #e5e7eb; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);">
                <h3 style="color: #003366; margin: 0 0 24px 0; font-size: 1.25rem; font-weight: 600; display: flex; align-items: center;">
                    📝 Détails du thème
                </h3>
                <div style="display: grid; gap: 20px;">
                    <div style="padding: 16px 0; border-bottom: 1px solid #f3f4f6;">
                        <span style="font-weight: 600; color: #374151; display: block; margin-bottom: 8px;">📌 Intitulé :</span>
                        <span style="color: #1f2937; font-size: 1.05rem; line-height: 1.5;">{{ $theme->intitule }}</span>
                    </div>
                    <div style="padding: 16px 0; border-bottom: 1px solid #f3f4f6;">
                        <span style="font-weight: 600; color: #374151; display: block; margin-bottom: 8px;">📄 Description :</span>
                        <div style="color: #4b5563; font-size: 1rem; line-height: 1.6; white-space: pre-line;">{{ $theme->description }}</div>
                    </div>
                    @if($theme->mots_cles)
                    <div style="padding: 16px 0; border-bottom: 1px solid #f3f4f6;">
                        <span style="font-weight: 600; color: #374151; display: block; margin-bottom: 8px;">🏷️ Mots-clés :</span>
                        <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                            @foreach(explode(',', $theme->mots_cles) as $mot)
                            <span style="background: #e0e7ff; color: #3730a3; padding: 4px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 500;">{{ trim($mot) }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    <div style="padding: 16px 0;">
                        <span style="font-weight: 600; color: #374151; display: block; margin-bottom: 8px;">📊 État :</span>
                        <span style="background: {{ $theme->etat === 'Modifié' ? '#fef3c7' : '#ddd6fe' }}; color: {{ $theme->etat === 'Modifié' ? '#92400e' : '#5b21b6' }}; padding: 6px 16px; border-radius: 20px; font-size: 0.9rem; font-weight: 600;">{{ $theme->etat }}</span>
                    </div>
                </div>
            </div>

            <!-- Action Message -->
            @if($theme->etat === 'Modifié')
            <div style="background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); border-radius: 12px; padding: 24px; margin: 32px 0; border-left: 4px solid #f59e0b;">
                <h4 style="color: #92400e; margin: 0 0 12px 0; font-size: 1.1rem; font-weight: 600;">⚡ Action requise</h4>
                <p style="margin: 0; color: #78350f; line-height: 1.6;">Veuillez prendre connaissance des modifications apportées au thème de votre stage.</p>
            </div>
            @else
            <div style="background: linear-gradient(135deg, #f3e8ff 0%, #e9d5ff 100%); border-radius: 12px; padding: 24px; margin: 32px 0; border-left: 4px solid #8b5cf6;">
                <h4 style="color: #6b21a8; margin: 0 0 12px 0; font-size: 1.1rem; font-weight: 600;">👀 À consulter</h4>
                <p style="margin: 0; color: #7c2d92; line-height: 1.6;">Veuillez prendre connaissance du thème proposé pour votre stage.</p>
            </div>
            @endif

            <!-- Action Button -->
            <div style="text-align: center; margin: 40px 0;">
                <a href="{{ route('stagiaire.stages.show', $stage->id) }}" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: #ffffff; padding: 16px 40px; text-decoration: none; border-radius: 10px; font-size: 1.1rem; font-weight: 600; display: inline-block; box-shadow: 0 4px 16px rgba(139, 92, 246, 0.3); transition: all 0.3s ease; border: none;">
                    🔍 Voir les détails du stage
                </a>
            </div>

            <!-- Instructions -->
            <div style="background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); border-radius: 12px; padding: 24px; margin: 32px 0; border-left: 4px solid #0ea5e9;">
                <h4 style="color: #0c4a6e; margin: 0 0 16px 0; font-size: 1.1rem; font-weight: 600;">💡 Informations importantes</h4>
                <div style="color: #0e7490;">
                    <div style="margin-bottom: 12px; padding: 8px 0; border-bottom: 1px solid rgba(14, 165, 233, 0.2);">
                        <strong>•</strong> Ne répondez pas directement à cet email
                    </div>
                    <div style="margin-bottom: 12px; padding: 8px 0; border-bottom: 1px solid rgba(14, 165, 233, 0.2);">
                        <strong>•</strong> Utilisez la plateforme pour toute communication
                    </div>
                    <div style="padding: 8px 0;">
                        <strong>•</strong> Contactez votre maître de stage via l'interface dédiée
                    </div>
                </div>
            </div>

            <!-- Signature -->
            <div style="margin-top: 40px; padding-top: 24px; border-top: 2px solid #f3f4f6;">
                <p style="margin: 0; color: #6b7280; font-size: 0.95rem; line-height: 1.5;">
                    Cordialement,<br>
                    <strong style="color: #374151;">{{ $stage->structure->libelle ?? config('app.name') }}</strong><br>
                    <span style="font-size: 0.85rem;">Plateforme de gestion des stages</span>
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: #ffffff; text-align: center; padding: 24px 32px;">
            <p style="margin: 0 0 8px 0; font-size: 0.9rem;">
                Ce message concerne votre stage à <strong>{{ $stage->structure->libelle ?? 'Structure non spécifiée' }}</strong>
            </p>
            <p style="margin: 0; font-size: 0.85rem; opacity: 0.8;">
                © {{ date('Y') }} Gestion des Stages - République du Bénin. Tous droits réservés.
            </p>
        </div>
    </div>
</body>
</html>