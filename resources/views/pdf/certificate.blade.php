<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $event->title }} Certificate</title>
    <style>
          * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        @page {
            margin: 0 !important;
            padding: 0 !important;
        }
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 0 !important;
            padding: 0 !important;

            background-position: center;
            background-repeat: no-repeat;
            background-size: contain;
            width: 100%;
            height: 900px;
            object-fit: contain;
        }
        .certificate {
            background-image: url('https://phplaravel-970963-4908828.cloudwaysapps.com/img/certificate/placeholder_certificate.png');
            position: relative;
            padding: 50px;
            padding-top:300px;
            width: 100%;
            height: 100%;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .certificate h1 {
            font-size: 50px;
            margin-bottom: 0;
            color: #000;
            text-shadow: 1px 1px 1px rgba(255,255,255,0.5);
        }
        .certificate h2 {
            font-size: 30px;
            margin: 20px 0;
            color: #000;
        }
        .certificate p {
            font-size: 20px;
            color: #000;
            margin: 10px 0;
        }
        .certificate .name {
            font-size: 30px;
            font-weight: bold;
            color: #000;
            margin: 20px 0;
        }
        .certificate .date {
            margin-top: 50px;
            font-size: 20px;
            color: #000;
        }
        .certificate-number {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 14px;
            color: #666;
        }

        .hours-served {
            font-size: 24px;
            color: #000;
            margin: 15px 0;
        }

        .slot-details {
            font-size: 18px;
            color: #444;
            margin: 10px 0;
        }

        .validation-note {
            position: absolute;
            bottom: 20px;
            left: 0;
            right: 0;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    @foreach($certificates as $certificate)
        <div class="certificate">
            <div class="certificate-number">
                Certificate No: {{ $certificate['certificateNumber'] }}
            </div>
            <h1>Certificate of Appreciation</h1>
            <h2>{{ $event->title }}</h2>
            <p>This certificate is proudly presented to</p>
            <p class="name">{{ $attendee->name }}</p>
            <div class="slot-details">
                <p>Volunteer Activity: {{ $certificate['record']->slot->name ?? 'General Participation' }}</p>
                <p class="hours-served">
                    @php
                        $hours = $certificate['hoursServed'];
                        echo number_format($hours, 1) . ($hours == 1 ? ' Hour' : ' Hours') . ' of Service';
                    @endphp
                </p>
            </div>
            <p>for their outstanding volunteer service and dedication.</p>
            <p class="date">Presented on: {{ \Carbon\Carbon::parse($event->end_date)->format('F d, Y') }}</p>

            <div class="validation-note">
                To verify this certificate's authenticity, please contact the Ayala Foundation administrator
                with the certificate number shown above.
            </div>
        </div>
        @if(!$loop->last)
            <div style="page-break-after: always;"></div>
        @endif
    @endforeach

</body>
</html>
