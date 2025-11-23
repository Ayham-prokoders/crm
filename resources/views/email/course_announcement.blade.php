<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Announcement</title>
</head>
<body>
    <h3>You are invited to the course: {{ $course->name }}</h3>
    <p>Dear {{ $trainer->name }},</p>
    <p>You have been invited to participate in the course titled "{{ $course->name }}".</p>
    <p>Class: {{ $class->title }}</p>

    @if ($class->schedule->online === 0)
        <p>Type: On-site</p>
        @if ($class->schedule->city->name)
            <p>Location: {{ $class->schedule->city->name }}</p>
        @endif
    @else
        <p>Type: Online</p>
    @endif
    <hr>

    {{-- If there's an image, display it here --}}
    {{-- @if ($image)
        <img src="{{ Storage::url($image) }}" alt="Course Image" />
    @endif --}}

    <p>Please respond to your availability using the link below:</p>

    <!-- This is the link where the instructor will respond -->
    <a href="{{ $url }}/apps/annoncment/" style="padding: 10px; background-color: blue; color: white; text-decoration: none;">Click here to respond</a>

    {{-- Alternative availability links --}}
    {{-- <a href="{{ $url }}/api/instructor/availability?course_id={{ $course->id }}&instructor_id={{ $trainer->id }}&class_id={{ $class->id }}&status=1" style="padding: 10px; background-color: green; color: white; text-decoration: none;">I'm Available</a>

    <a href="{{ $url }}/api/instructor/availability?course_id={{ $course->id }}&instructor_id={{ $trainer->id }}&class_id={{ $class->id }}&status=0" style="padding: 10px; background-color: red; color: white; text-decoration: none;">I'm Not Available</a> --}}

</body>
</html>
