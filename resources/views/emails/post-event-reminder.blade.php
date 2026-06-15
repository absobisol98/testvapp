<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            padding: 20px;
            background-color: #f55e1d;
        }
        .logo {
            max-width: 200px;
            height: auto;
        }
        .content {
            padding: 30px;
            background-color: #ffffff;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #f55e1d;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            padding: 20px;
            background-color: #f5f5f5;
            text-align: center;
            color: #666666;
        }
        .highlight {
            color: #f55e1d;
            font-weight: bold;
        }
        .event-details {
            background-color: #f8f8f8;
            padding: 15px;
            border-left: 4px solid #0433ff;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ asset('img/logo-white.png') }}" alt="Ayala Foundation" class="logo">
    </div>

    <div class="content">
        <h2>Opportunity Ending Soon: <span class="highlight">{{ $event->name }}</span></h2>

        <p>Dear {{ $registration->name }},</p>

        <p>The event will be concluding in 15 minutes. We hope you had a meaningful and enriching experience.</p>

        <div class="event-details">
            <h3 style="color: #0433ff;">Important Reminders:</h3>
            <ul>
                <li>Please ensure you have completed all time out procedures</li>
                <li>Collect any certificates or materials if applicable</li>
                <li>A feedback survey will be sent to you shortly</li>
            </ul>
        </div>

        <p>Your feedback is valuable to us. Please watch out for our post-event survey in your email.</p>

        <p style="color: #0433ff; font-weight: bold;">Thank you for your active participation!</p>
    </div>

    <div class="footer">
        <p>This is an automated message from Ayala Foundation.</p>
        <p>If you have any questions, please contact us at {{ config('mail.from.address') }}</p>
    </div>
</body>
</html>
