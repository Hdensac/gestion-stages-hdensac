<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Vérification de votre adresse email</title>
</head>
<body style="font-family: Arial, sans-serif; color: #222;">
    <div style="max-width: 600px; margin: auto; border:1px solid #eee; padding: 24px;">
        <h2 style="color: #3490dc;">Vérification de votre adresse email</h2>
        <p>Bonjour {{ $user->prenom ?? '' }} {{ $user->nom ?? '' }},</p>
        <p>
            Merci de vous être inscrit sur notre plateforme.<br>
            Pour finaliser votre inscription, veuillez cliquer sur le bouton ci-dessous afin de vérifier votre adresse email.
        </p>
        <div style="text-align: center; margin: 32px 0;">
            <a href="{{ $actionUrl }}" style="background: #3490dc; color: #fff; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block;">
                Vérifier mon adresse email
            </a>
        </div>
        <p>
            Si le bouton ne fonctionne pas, copiez et collez ce lien dans votre navigateur :<br>
            <a href="{{ $actionUrl }}">{{ $actionUrl }}</a>
        </p>
        <p style="margin-top: 32px;">Cordialement,<br>L'équipe Gestion des Stages</p>
        <hr>
        <small>© {{ date('Y') }} Gestion des Stages. Tous droits réservés.</small>
    </div>
</body>
</html>