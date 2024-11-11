<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Interview Application Received</title>
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

        <h1>New Interview Application Received</h1>

<p>Dear {{ $interview->inchargeUser->name }},</p>

<p>A candidate has applied for an interview for the following position:</p>

<ul>
    <li>Job Title: {{ $interview->vacancy->job_title }}</li>
    <li>Company: {{ $interview->vacancy->company->name }}</li>
    <li>Candidate: {{ $interview->candidate->name }}</li>
</ul>

<p>Please check the interview details on the Interview Status List page.</p>

<p>For more details, please visit: <a href="{{ route('interview.details', ['interview' => $interview->id]) }}">Interview Details</a></p>

<p>Best regards,<br>{{ config('app.name') }}</p>
        <br>

        <div class="footer">
            <p style="text-align: center; font-size: 6; color: #000000;">This email is automatically generated. So,
                Please do not reply.</p>
            <br>
            <p style="text-align: center; font-size: 12px;">Copyright © {{ date('Y') }} {{ config('app.name') }}.
                All
                rights reserved.</p>
        </div>
    </div>
</body>

</html>
