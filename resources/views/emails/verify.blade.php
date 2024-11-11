<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email Address</title>
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
            <a href="{{ config('app.url') }}"
                style="color: #3d4852; font-size: 19px; font-weight: bold; text-decoration: none;">
                {{ config('app.name') }}
            </a>
        </div>

        <h1>Verify Your Email Address</h1>
        <h2>Hello!</h2>
        <p>Thank you for signing up for {{ config('app.name') }}. We're excited to have you get started. First, you need
            to confirm your email address.</p>
        <p>Please click the button below to verify your email address:</p>

            <table style="margin-top: 10px; margin-bottom: 10px" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                <tr>
                    <td align="center">
                        <a href="{{ $verificationUrl }}" style="display: inline-block; padding: 10px 20px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 5px;">Verify Email</a>
                    </td>
                </tr>
            </table>
        <p>This email has been sent to those who have registered on {{ config('app.name') }}. If you did not make this
            request or create an account, please ignore this email.</p>
            <p>This verification link will expire on {{ $expirationDateTime->format('F j, Y \a\t g:i A') }} ({{ $expirationDateTime->diffForHumans() }}). If the validation link has expired, please request a new verification email.</p>

        <p>Thank you for joining our platform!</p>
        <br>

        <div class="footer">
            <p style="text-align: center; font-size: 6; color: #000000;">This email is automatically generated. So, Please do not reply.</p>
            <br>
            <p style="text-align: center; font-size: 12px;">Copyright © {{ date('Y') }} {{ config('app.name') }}. All
                rights reserved.</p>
        </div>
    </div>
</body>

</html>
