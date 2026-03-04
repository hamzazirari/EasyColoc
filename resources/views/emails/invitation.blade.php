<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: Arial, sans-serif; padding: 20px;">

    <h2>Vous avez été invité à rejoindre une colocation !</h2>

    <p>Vous avez été invité à rejoindre la colocation : <strong>{{ $invitation->colocation->name }}</strong></p>

    <p>Cliquez sur le bouton ci-dessous pour accepter l'invitation :</p>

    <a href="{{ url('/invitation/accept/' . $invitation->token) }}" 
       style="background-color: #4f46e5; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
        Accepter l'invitation
    </a>

    <br><br>

    <a href="{{ url('/invitation/refuse/' . $invitation->token) }}"
       style="color: red;">
        Refuser l'invitation
    </a>

    <p style="color: gray; font-size: 12px;">
        Ce lien expire dans 48 heures.
    </p>

</body>
</html>