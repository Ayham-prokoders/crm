<!DOCTYPE html>
<html>
<head>
    <title>Welcome to the Class</title>
</head>
<body>
    <h3>Welcome {{ $instructor->name }}</h3>
    <p>You've been assigned as the instructor for the class <strong>{{ $class->title }}</strong>
    for course <strong>{{ $class->course->name ?? $class->externalCourse->name ?? 'Unknown Course' }}</strong>
    At {{ $class->schedule->date ?? $class->externalSchedule->date ?? 'No schedule date'}}
    </p>
    <p>We are excited to have you onboard!</p>
</body>
</html>
