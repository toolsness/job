<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Representative Account Registration</title>
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
            background-color: #000000;
            color: #ffffff;
            text-decoration: none;
            border-radius: 4px;
        }
        .a {
            display: inline-block;
            padding: 8px 18px;
            background-color: #000000;
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

        <h1>Company Representative Account Created</h1>
        <h2>Hello {{ $name }}!</h2>
        <p>Your Company Representative account for {{ $companyName }} has been created and is pending approval from your Company Admin.</p>

        <p>Once approved, you can log in using the following credentials:</p>
        <ul>
            <li>Email: {{ $email }}</li>
            <li>Password: {{ $password }}</li>
        </ul>

        <p>You will receive another email notification once your account is approved.</p>
        <p>You can change your password anytime by visiting:</p>
        <a href="{{ route('company.password.request') }}" class="button">Change Password</a>

        <div class="footer">
            <p style="text-align: center; font-size: 6; color: #000000;">This email is automatically generated. Please do not reply.</p>
            <br>
            <p style="text-align: center; font-size: 12px;">Copyright © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
