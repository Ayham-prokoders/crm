<!DOCTYPE html>
<html>
<head>
    <title>Class Assignment Update</title>
</head>
<body>
    <h3>Hello {{ $instructor->name }}</h3>
    <p>Thank you for showing interest and availability for the class <strong>{{ $class->title }}</strong>
    from course {{ $class->course->name }}
    </p>
    <p>Unfortunately, we have assigned this class to another instructor.</p>
    <p>We appreciate your efforts and look forward to more opportunities with you.</p>
</body>
</html>
