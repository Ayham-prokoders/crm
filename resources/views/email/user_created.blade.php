<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Welcome to LPC CRM</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: auto; padding: 20px; border: 1px solid #eee; border-radius: 8px; }
        .credentials { background-color: #f5f5f5; padding: 10px; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Hello {{ $user->name }},</h2>
        <p>Welcome to <strong>London Premium Center CRM</strong> Your login details are ready.</p>

        <div class="credentials">
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Temporary Access Code:</strong> {{ $password }}</p>
        </div>

        <p>You can now <a href="{{ $link ?? env('PROJECT_FRONTEND') }}"> access your account</a> using the details above. For security, you may update your access code once logged in.</p>

        <hr>
        <p style="font-size:12px;color:#777;">
            This is an official communication from London Premium Center.
        </p>
    </div>
</body>
</html>
