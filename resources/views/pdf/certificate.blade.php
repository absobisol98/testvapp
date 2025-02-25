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
            background-image: url('https://phplaravel-970963-4908828.cloudwaysapps.com/img/certificate/placeholder_certificate.png');
            background-position: center;
            background-repeat: no-repeat;
            background-size: contain;
            width: 100%;
            height: 900px;
            object-fit: contain;
        }
        .certificate {
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
    </style>
</head>
<body>
    <div class="certificate">
        <h1>Certificate of Appreciation</h1>
        <h2>{{ $event->title }}</h2>
        <p>This certificate is proudly presented to</p>
        <p class="name">{{ $attendee->name }}</p>
        <p>for their outstanding volunteer service and dedication.</p>
        <p class="date">Presented on: {{ now()->format('F d, Y') }}</p>
    </div>
</body>
</html>
