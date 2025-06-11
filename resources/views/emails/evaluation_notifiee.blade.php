<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Évaluation de votre stage</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; background: linear-gradient(135deg, #f5f8fa 0%, #e8f2f8 100%); color: #2c3e50; margin: 0; padding: 20px; line-height: 1.6;">
    <div style="max-width: 650px; margin: 20px auto; background: #ffffff; border-radius: 16px; box-shadow: 0 8px 32px rgba(0, 51, 102, 0.12); overflow: hidden; border: 1px solid rgba(52, 144, 220, 0.1);">
        
        <!-- Header Section -->
        <div style="background: linear-gradient(135deg, #059669 0%, #047857 100%); padding: 40px 24px; text-align: center; position: relative;">
            <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, #34d399, #6ee7b7, #34d399);"></div>
            <img src="{{ asset('images/logoministere.png') }}" alt="Logo Ministère" style="height: 70px; margin-bottom: 16px; filter: brightness(1.1);">
            <h1 style="color: #ffffff; margin: 0; font-size: 1.75rem; font-weight: 600; letter-spacing: -0.5px;">Gestion des Stages</h1>
            <p style="color: rgba(255, 255, 255, 0.9); margin: 8px 0 0 0; font-size: 0.95rem;">Évaluation de votre stage</p>
        </div>

        <!-- Main Content -->
        <div style="padding: 40px 32px;">
            
            <!-- Subject Header -->
            <div style="background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); border: 2px solid #059669; border-radius: 12px; padding: 24px; margin: 32px 0; text-align: center; position: relative;">
                <div style="position: absolute; top: -1px; left: 20px; background: #ffffff; padding: 0 12px; color: #059669; font-weight: 600; font-size: 0.85rem;">📊 ÉVALUATION</div>
                <div style="margin-top: 8px;">
                    <h2 style="color: #065f46; margin: 0 0 8px 0; font-size: 1.5rem; font-weight: 700;">Évaluation de votre stage</h2>
                    <p style="margin: 0; color: #374151;">Bonjour <strong style="color: #059669;">{{ $stagiaire->prenom }} {{ $stagiaire->nom }}</strong></p>
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
                        <span style="color: #6b7280;">{{ $stage->structure->libelle ?? 'Structure non spécifiée' }}</span>
                    </div>
                    <div style="display: flex; align-items: flex-start; padding: 12px 0;">
                        <span style="font-weight: 600; color: #374151; min-width: 140px; display: inline-block;">👨‍💼 Maître de stage :</span>
                        <span style="color: #6b7280;">{{ $evaluation->maitre_stage ?? 'Non spécifié' }}</span>
                    </div>
                </div>
            </div>

            <!-- Note totale Section -->
            <div style="background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); border: 2px solid #10b981; border-radius: 12px; padding: 28px; margin: 32px 0; text-align: center;">
                <h3 style="color: #065f46; margin: 0 0 16px 0; font-size: 1.4rem; font-weight: 700;">🎯 Note totale</h3>
                <div style="background: #ffffff; border-radius: 50%; width: 120px; height: 120px; margin: 0 auto 16px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);">
                    <span style="font-size: 2.5rem; font-weight: 800; color: #059669;">{{ $evaluation->note_totale }}</span>
                    <span style="font-size: 1.2rem; color: #6b7280; margin-left: 4px;">/20</span>
                </div>
                <p style="margin: 0; color: #065f46; font-weight: 600;">Votre maître de stage a terminé votre évaluation</p>
            </div>

            <!-- Détail des critères -->
            <div style="background: #ffffff; border-radius: 12px; padding: 28px; margin: 32px 0; border: 1px solid #e5e7eb; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);">
                <h4 style="color: #1f2937; margin: 0 0 24px 0; font-size: 1.2rem; font-weight: 600; display: flex; align-items: center;">
                    📋 Détail des critères d'évaluation
                </h4>
                
                <div style="display: grid; gap: 12px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 16px; background: #f9fafb; border-radius: 8px; border-left: 4px solid #059669;">
                        <span style="color: #374151; font-weight: 500;">⏰ Ponctualité / Présences</span>
                        <span style="background: #059669; color: white; padding: 4px 12px; border-radius: 20px; font-weight: 600; font-size: 0.9rem;">{{ $evaluation->ponctualite }}/2</span>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 16px; background: #f9fafb; border-radius: 8px; border-left: 4px solid #059669;">
                        <span style="color: #374151; font-weight: 500;">🚀 Motivation / Initiative</span>
                        <span style="background: #059669; color: white; padding: 4px 12px; border-radius: 20px; font-weight: 600; font-size: 0.9rem;">{{ $evaluation->motivation }}/2</span>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 16px; background: #f9fafb; border-radius: 8px; border-left: 4px solid #059669;">
                        <span style="color: #374151; font-weight: 500;">🧠 Capacités d'apprendre</span>
                        <span style="background: #059669; color: white; padding: 4px 12px; border-radius: 20px; font-weight: 600; font-size: 0.9rem;">{{ $evaluation->capacite_apprendre }}/2</span>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 16px; background: #f9fafb; border-radius: 8px; border-left: 4px solid #059669;">
                        <span style="color: #374151; font-weight: 500;">⭐ Qualité du travail</span>
                        <span style="background: #059669; color: white; padding: 4px 12px; border-radius: 20px; font-weight: 600; font-size: 0.9rem;">{{ $evaluation->qualite_travail }}/2</span>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 16px; background: #f9fafb; border-radius: 8px; border-left: 4px solid #059669;">
                        <span style="color: #374151; font-weight: 500;">⚡ Rapidité d'exécution</span>
                        <span style="background: #059669; color: white; padding: 4px 12px; border-radius: 20px; font-weight: 600; font-size: 0.9rem;">{{ $evaluation->rapidite_execution }}/2</span>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 16px; background: #f9fafb; border-radius: 8px; border-left: 4px solid #059669;">
                        <span style="color: #374151; font-weight: 500;">🎯 Jugement</span>
                        <span style="background: #059669; color: white; padding: 4px 12px; border-radius: 20px; font-weight: 600; font-size: 0.9rem;">{{ $evaluation->jugement }}/2</span>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 16px; background: #f9fafb; border-radius: 8px; border-left: 4px solid #059669;">
                        <span style="color: #374151; font-weight: 500;">💪 Esprit de motivation</span>
                        <span style="background: #059669; color: white; padding: 4px 12px; border-radius: 20px; font-weight: 600; font-size: 0.9rem;">{{ $evaluation->esprit_motivation }}/2</span>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 16px; background: #f9fafb; border-radius: 8px; border-left: 4px solid #059669;">
                        <span style="color: #374151; font-weight: 500;">🤝 Esprit de collaboration</span>
                        <span style="background: #059669; color: white; padding: 4px 12px; border-radius: 20px; font-weight: 600; font-size: 0.9rem;">{{ $evaluation->esprit_collaboration }}/2</span>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 16px; background: #f9fafb; border-radius: 8px; border-left: 4px solid #059669;">
                        <span style="color: #374151; font-weight: 500;">🛡️ Sens de responsabilité</span>
                        <span style="background: #059669; color: white; padding: 4px 12px; border-radius: 20px; font-weight: 600; font-size: 0.9rem;">{{ $evaluation->sens_responsabilite }}/2</span>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 16px; background: #f9fafb; border-radius: 8px; border-left: 4px solid #059669;">
                        <span style="color: #374151; font-weight: 500;">💬 Communication</span>
                        <span style="background: #059669; color: white; padding: 4px 12px; border-radius: 20px; font-weight: 600; font-size: 0.9rem;">{{ $evaluation->communication }}/2</span>
                    </div>
                </div>
            </div>

            <!-- Commentaire général (si présent) -->
            @if($evaluation->commentaire_general)
            <div style="background: #ffffff; border-radius: 12px; padding: 28px; margin: 32px 0; border: 1px solid #e5e7eb; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);">
                <h4 style="color: #1f2937; margin: 0 0 20px 0; font-size: 1.1rem; font-weight: 600; display: flex; align-items: center;">
                    💭 Commentaire général
                </h4>
                <div style="color: #374151; font-size: 1rem; line-height: 1.7; background: #f8fafc; padding: 20px; border-radius: 8px; border-left: 4px solid #059669;">
                    {!! nl2br(e($evaluation->commentaire_general)) !!}
                </div>
            </div>
            @endif

            <!-- Action Button -->
            <div style="text-align: center; margin: 40px 0;">
                <a href="{{ route('stagiaire.stages.show', $stage->id) }}" style="display: inline-block; background: linear-gradient(135deg, #059669 0%, #047857 100%); color: #ffffff; text-decoration: none; padding: 16px 32px; border-radius: 12px; font-weight: 600; font-size: 1.1rem; box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3); transition: all 0.3s ease;">
                    📄 Voir les détails du stage
                </a>
            </div>

            <!-- Important Notice -->
            <div style="background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); border-radius: 12px; padding: 24px; margin: 32px 0; border-left: 4px solid #3b82f6;">
                <h4 style="color: #1e40af; margin: 0 0 16px 0; font-size: 1.1rem; font-weight: 600;">💡 Information importante</h4>
                <div style="color: #1e3a8a;">
                    <div style="margin-bottom: 12px; padding: 8px 0; border-bottom: 1px solid rgba(59, 130, 246, 0.2);">
                        <strong>•</strong> Cette évaluation reflète votre performance durant le stage
                    </div>
                    <div style="margin-bottom: 12px; padding: 8px 0; border-bottom: 1px solid rgba(59, 130, 246, 0.2);">
                        <strong>•</strong> N'hésitez pas à discuter des résultats avec votre maître de stage
                    </div>
                    <div style="padding: 8px 0;">
                        <strong>•</strong> Utilisez la plateforme pour toute communication
                    </div>
                </div>
            </div>

            <!-- Contact Info -->
            <div style="background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%); border: 1px solid #bbf7d0; border-radius: 12px; padding: 20px; margin: 32px 0; text-align: center;">
                <h4 style="color: #065f46; margin: 0 0 8px 0; font-size: 1.1rem; font-weight: 600;">📞 Besoin d'aide ?</h4>
                <p style="margin: 0; color: #166534; font-size: 0.95rem;">Pour toute question concernant cette évaluation, contactez votre maître de stage via la plateforme de gestion des stages.</p>
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