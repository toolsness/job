<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.5;
            color: #3d4852;
        }
        .container {
            max-width: 570px;
            margin: 0 auto;
            padding: 32px;
            background-color: #ffffff;
            border: 1px solid #e8e5ef;
            border-radius: 2px;
        }
        .header {
            text-align: center;
            padding: 25px 0;
        }
        .button {
            display: inline-block;
            padding: 8px 18px;
            background-color: #cecfd1;
            color: #ffffff;
            text-decoration: none;
            border-radius: 4px;
        }
        .footer {
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid #e8e5ef;
            font-size: 14px;
            color: #b0adc5;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="{{ config('app.url') }}" style="color: #3d4852; font-size: 19px; font-weight: bold; text-decoration: none;">
                {{ config('app.name') }}
            </a>
        </div>

        <h1>Hello!</h1>
        <p>You are receiving this email because we received a password reset request for your account.</p>

        <table style="margin-top: 10px; margin-bottom: 10px" width="100%" cellpadding="0" cellspacing="0" role="presentation">
            <tr>
                <td align="center">
                    <a href="{{ $resetUrl }}" style="display: inline-block; padding: 10px 20px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 5px;" class="button" target="_blank">Reset Password</a>
                </td>
            </tr>
        </table>

        <p>This password reset link will expire in 5 minutes.</p>
        <p>If you did not request a password reset, no further action is required.</p>

        <p>Regards,<br>{{ config('app.name') }}</p>

        <div class="footer">
            <p>If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser: <br>
            <a href="{{ $resetUrl }}">{{ $resetUrl }}</a></p>
        </div>
        <p style="text-align: center; font-size: 6; color: #000000;">This email is automatically generated. So, Please do not reply.</p>
        <br>
        <p style="text-align: center; font-size: 12px;">Copyright © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</body>
</html>
