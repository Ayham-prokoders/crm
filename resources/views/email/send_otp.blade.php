<!DOCTYPE html>
<html>
<head>
    <title>OTP Verification</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <style type="text/css">
        body { margin: 0; padding: 0; font-family: sans-serif; }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #F7F7F7;">
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td style="padding: 20px 0 30px 0;">

                <table align="center" border="0" cellpadding="0" cellspacing="0" width="600" style="border-collapse: collapse; border: 1px solid #cccccc; background-color: #ffffff;">
                    <tr>
                        <td align="center" style="padding: 40px 0 30px 0; background-color: #fda500ff; color: #ffffff; font-size: 28px; font-weight: bold;">
                            Your One-Time Password
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 40px 30px 40px 30px;">
                            <h2 style="color: #333333; margin: 0 0 20px 0;">Hello {{ $userName }},</h2>
                            <p style="margin: 0 0 20px 0; color: #555555; font-size: 16px; line-height: 1.4;">
                                Here is your one-time password to access your account.
                            </p>
                            
                            <div style="text-align: center; padding: 20px; background-color: #f2f2f2; border-radius: 4px; margin-bottom: 30px;">
                                <p style="margin: 0; font-size: 36px; font-weight: bold; letter-spacing: 4px; color: #333333;">
                                    {{ $otp }}
                                </p>
                            </div>
                            
                            <p style="margin: 0 0 20px 0; color: #555555; font-size: 16px;">
                                This code will expire in 5 minutes.
                            </p>
                            <p style="margin: 0; color: #888888; font-size: 14px;">
                                If you didn't request this, you can safely ignore this email.
                            </p>
                        </td>
                    </tr>
                    
                </table>

            </td>
        </tr>
    </table>
</body>
</html>