<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $survey->name }} - Survey Invitation</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f5f7fa; padding: 30px; color: #333;">
    <div style="max-width: 600px; margin: auto; background-color: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
        <h2 style="color: #2c3e50;">Hello {{ $user->name }},</h2>

        <p style="font-size: 16px;">You have been invited to complete a survey titled:</p>

        <h3 style="color: #3490dc;">{{ $survey->name }}</h3>

        <p style="font-size: 15px;">
            Your input is highly valuable to us. Please take a few moments to share your feedback by clicking the button below:
        </p>

        <p style="text-align: center; margin: 30px 0;">
            <a href="{{ $link }}" style="background-color: #3490dc; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-size: 16px;">Start Survey</a>
        </p>

        <p style="font-size: 14px; color: #777;">If you have any questions, feel free to reach out to our team.</p>

        <hr style="margin-top: 30px; border: none; border-top: 1px solid #eee;">
        <p style="font-size: 12px; color: #aaa;">
            This survey was sent to you by LPC team.
        </p>
    </div>
</body>
</html>
