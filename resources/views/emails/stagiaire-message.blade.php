<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $data['subject'] }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f0f7f0;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #4CAF50;
        }
        .content {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .footer {
            font-size: 12px;
            color: #777;
            border-top: 1px solid #eee;
            padding-top: 15px;
            margin-top: 20px;
        }
        h1 {
            color: #2e7d32;
            font-size: 24px;
            margin-top: 0;
        }
        .signature {
            margin-top: 30px;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $data['subject'] }}</h1>
        <p>Message de votre maître de stage</p>
    </div>
    
    <div class="content">
        {!! nl2br(e($data['message'])) !!}
    </div>
    
    <div class="signature">
        <p>
            Cordialement,<br>
            {{ $data['agent']->prenom }} {{ $data['agent']->nom }}<br>
            Maître de Stage<br>
            {{ $data['stage']->structure->libelle ?? 'Structure non spécifiée' }}
        </p>
    </div>
    
    <div class="footer">
        <p>Ce message concerne votre stage à {{ $data['stage']->structure->libelle ?? 'Structure non spécifiée' }}.</p>
        <p>Veuillez ne pas répondre directement à cet email. Pour toute question, contactez votre maître de stage via la plateforme.</p>
    </div>
</body>
</html>
