<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteer Certificate</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 50px;
        }
        .certificate {
            border: 10px solid #ddd;
            padding: 50px;
            width: 80%;
            margin: 0 auto;
        }
        .certificate h1 {
            font-size: 50px;
            margin-bottom: 0;
        }
        .certificate p {
            font-size: 20px;
        }
        .certificate .name {
            font-size: 30px;
            font-weight: bold;
        }
        .certificate .date {
            margin-top: 50px;
            font-size: 20px;
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
        <p class="date">Date: {{ now() }}</p>
    </div>
</body>
</html>
