<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>Attendance Report</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; text-align: center; }
        .logo-container { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #2f5597; color: white; padding: 10px; }
        td { padding: 10px; text-align: center; }
        .day-column { width: 30%; }
        .date-column { width: 35%; }
        .status-column { width: 35%; }
    </style>
</head>
<body>
    <div class="logo-container">
        <img src="{{ public_path('whiteLogo.png') }}" width="100px" height="auto" alt="LPC Logo">
    </div>
    <h2>Attendance Report for {{ $user->name }} in Class {{ $class->title }}</h2>
    <p>Course: {{ $class->course->name }}</p>

    <table>
        <thead>
            <tr>
                <th class="day-column">Days</th>
                <th class="date-column">Dates</th>
                <th class="status-column">Attendee Signature</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($class->sessions as $session)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($session->date)->format('l') }}</td>
                    <td>{{ \Carbon\Carbon::parse($session->date)->format('d/m/Y') }}</td>
                    <td>{{ $session->attendance?->status ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
