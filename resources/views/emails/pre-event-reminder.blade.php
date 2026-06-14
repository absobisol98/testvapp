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
            background-color: #ff7b00;
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
            background-color: #ff7b00;
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
            color: #ff7b00;
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
        <h2>Opportunity Starting Soon: <span class="highlight">{{ $event->name }}</span></h2>


        <p>Dear {{ $registration->name }},</p>

        <p>This is a gentle reminder that your registered event will begin in 15 minutes.</p>

        <div class="event-details">
            <h3 style="color: #0433ff;">Opportunity Details:</h3>
            <p><strong>Opportunity:</strong> {{ $event->title }}</p>
            <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($event->start_date)->format('F d, Y') }}</p>
            <p><strong>Time:</strong> {{ \Carbon\Carbon::parse($event->start_time)->format('g:i A') }}</p>
            <p><strong>Venue:</strong> {{ $event->location }}</p>
        </div>

        <p>Please ensure that you:</p>
        <ul>
            <li>Have your registration details ready</li>
            <li>Time in upon arrival</li>
            <li>Sign any necessary attendance sheets</li>
        </ul>

    </div>

    <div class="footer">
        <p>This is an automated message from Ayala Foundation.</p>
        <p>If you have any questions, please contact us at {{ config('mail.from.address') }}</p>
    </div>
</body>
</html>

