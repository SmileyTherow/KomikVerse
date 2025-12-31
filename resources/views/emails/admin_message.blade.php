<!-- Simple plain HTML email -->
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ $subject ?? 'Pesan dari Admin' }}</title>
</head>

<body>
    <p>Halo,</p>

    <div style="white-space: pre-line;">
        {{ $messageBody }}
    </div>

    <p>Salam,<br>{{ $adminName }}</p>
</body>

</html>
