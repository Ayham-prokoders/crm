<!DOCTYPE html>
<html lang="en">
@php
    $footer_contact1 = \App\Models\FooterSetting::where('name', 'footer_contact1')->first();
    $contact_message1 = $footer_contact1 ? $footer_contact1->value : '';
    $footer_contact2 = \App\Models\FooterSetting::where('name', 'footer_contact2')->first();
    $contact_message2 = $footer_contact2 ? $footer_contact2->value : '';
    $footer_contact3 = \App\Models\FooterSetting::where('name', 'footer_contact3')->first();
    $contact_message3 = $footer_contact3 ? $footer_contact3->value : '';
    $footer_contact4 = \App\Models\FooterSetting::where('name', 'footer_contact4')->first();
    $contact_message4 = $footer_contact4 ? $footer_contact4->value : '';

    $footer_categories = \App\Models\FooterSetting::where('name', 'footer_categories')->first();
    $footer_cities = \App\Models\FooterSetting::where('name', 'footer_cities')->first();

    $footer_categories_arr = explode(',', $footer_categories ? $footer_categories->value : '');
    $footer_cities_arr = explode(',', $footer_cities ? $footer_cities->value : '');

    $_footer_categories = \Modules\Lms\Models\Category::whereIn('id', $footer_categories_arr)->get();
    $_footer_cities = \Modules\Lms\Models\City::whereIn('id', $footer_cities_arr)->get();

    $footer_whatsapp = \App\Models\FooterSetting::where('name', 'footer_whatsapp')->first();
    $_footer_whatsapp = $footer_whatsapp ? $footer_whatsapp->value : '';

    $footer_facebook = \App\Models\FooterSetting::where('name', 'footer_facebook')->first();
    $_footer_facebook = $footer_facebook ? $footer_facebook->value : '';

    $footer_twitter = \App\Models\FooterSetting::where('name', 'footer_twitter')->first();
    $_footer_twitter = $footer_twitter ? $footer_twitter->value : '';

    $footer_linkedin = \App\Models\FooterSetting::where('name', 'footer_linkedin')->first();
    $_footer_linkedin = $footer_linkedin ? $footer_linkedin->value : '';
@endphp
@php
    $languages = json_decode($instructor->languages, true);
    // $specilized_topics = json_decode($instructor->specilized_topics, true);
    $experience = json_decode($instructor->experience, true);
    $qualification = json_decode($instructor->qualification, true);
    $courses = json_decode($instructor->course_experience_lpc, true);
    $certifications = json_decode($instructor->certification, true);
    $publications = json_decode($instructor->publications, true);
    $speaking_engagements = json_decode($instructor->speaking_engagements, true);
    $awards = json_decode($instructor->awards, true);
    $testimonials = json_decode($instructor->testimonials, true);
    $trainingModes = json_decode($instructor->training_modes, true);
    $countryAvailability = json_decode($instructor->country_availability, true);
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Profile</title>

    <link rel="stylesheet" href="{{ asset('css/new-style/profile.css') }}">

</head>

<body>
    <div class="container1">
        <!-- Header Section -->
        <header class="header">
            <div class="logo">
                <img src="{{ asset('whiteLogo.png') }}" alt="LPC Logo">

            </div>
            <div class="submission-date">
                <p>Date Of Submission: {{ $instructor->date_of_submission }}</p>
            </div>
        </header>
        <div class="main">
            <!-- Instructor Profile Section -->
            <section class="profile">

                <div class="profile-image">
                    @if($user->image)
                    <img src="{{ $user->image ? asset($user->image) : asset('images/default-profile.png') }}"
                        alt="Instructor">
                    @endif
                </div>
                <div class="profile-info">
                    <h1>{{ $user->name }} {{ $user->last_name }}</h1>
                    <div class="contact-info">
                        @if($user->email)
                        <p><img src="{{ asset('images/email-svgrepo-com.svg') }}" alt="Email">
                            {{ $user->email }}
                        </p>
                        @endif
                        @if ($instructor->city)
                        <p>
                            <img src="{{ asset('images/location-pin-svgrepo-com.svg') }}" alt="Location">
                                {{ $instructor->city->name ?? '' }}
                        </p>
                        @endif
                        @if($user->phone)
                        <p><img src="{{ asset('images/phone.svg') }}" alt="Email"> {{ $user->phone }}</p>
                        @endif
                    </div>
                    @if($instructor->rating)
                        <div class="rating">
                            <p style="padding-right: 10px;"><img src="{{ asset('images/rate.svg') }}" alt="Email"> </p>
                            <div class="stars">
                                @php
                                    $fullStars = floor($instructor->rating);
                                    $halfStar = $instructor->rating - $fullStars >= 0.5;
                                @endphp
                                @for ($i = 0; $i < $fullStars; $i++)
                                    <img class="icon2" src="{{ asset('images/star-svgrepo-com.svg') }}" />
                                @endfor
                                @if ($halfStar)
                                    <img class="icon2" src="{{ asset('images/star-half-svgrepo-com.svg') }}" />
                                @endif
                                @for ($i = 0; $i < 5 - $fullStars - ($halfStar ? 1 : 0); $i++)
                                    <img class="icon2" src="{{ asset('images/star-e-svgrepo-com.svg') }}" />
                                @endfor
                            </div>
                        </div>
                    @endif
                </div>

            </section>


            <!-- Main Content Section -->
            <main>
                <div class="left-column">
                    @if (!is_null($instructor->professional_summary) && $instructor->professional_summary != '')
                        <section class="summary">
                            <h2>Personal Summary</h2>
                            <p>{!! $instructor->professional_summary !!}</p>
                        </section>
                    @endif
                    @if (($instructor->topics && $instructor->topics->count() > 0) || ($languages && $languages->count() > 0))
                        <section class="expertise">
                            <h2>Areas of Expertise</h2>
                            @if ($instructor->topics && $instructor->topics->count() > 0)
                                <h4 style="margin-left:10px;">Specialized Topics</h4>
                                @foreach ($instructor->topics as $topic)
                                    <div class="expertise-item border-left"
                                        style="margin-bottom:10px; margin-left:10px;">
                                        <p>{{ $topic->title }}</p>
                                    </div>
                                @endforeach
                            @endif

                            @if (is_array($languages))
                                <h4 style="margin-left:10px;">Languages</h4>
                                @foreach ($languages as $language)
                                    <div
                                        class="expertise-item border-left"style="margin-bottom:10px; margin-left:10px;">
                                        <p>{{ $language['title'] ?? '' }} : {{ $language['level'] ?? '' }}</p>
                                    </div>
                                @endforeach
                            @endif

                        </section>
                    @endif
                    @if ($testimonials)
                        <section class="testimonial-reference">
                            <h2 style="margin-bottom: 30px;">Testimonial/Reference</h2>
                            @if (is_array($testimonials))
                                @foreach ($testimonials as $testimonial)
                                    <ul class="reference-details border-left"
                                        style="margin-left:10px; margin-bottom:20px;">
                                        <li><strong>Name:</strong>{{ $testimonial['name'] ?? '' }}</li>
                                        <li><strong>Position:</strong> {{ $testimonial['position'] ?? '' }}</li>
                                        <li><strong>Organization:</strong> Organization</li>
                                        <li><strong>Contact Information:</strong>
                                            {{ $testimonial['contact_info'] ?? '' }}
                                        </li>
                                        <li><strong>Testimonial:</strong> {{ $testimonial['content'] ?? '' }}
                                        </li>
                                    </ul>
                                @endforeach
                            @endif
                        </section>
                    @endif
                </div>

                <div class="right-column">
                    @if ($experience)
                    <section class="experience">
                        <h2 style="margin-bottom: 30px;">Professional Experience</h2>
                        @if (is_array($experience))
                            @foreach ($experience as $exp)
                                <ul class="border-left" style="margin-bottom: 20px;">
                                    <li><strong>Current Position:</strong>{{ $exp['job_title'] ?? '' }}</li>
                                    <li><strong>Organization:</strong>{{ $exp['organization'] ?? '' }}</li>
                                    <li><strong>Start Date:</strong>{{ $exp['start_date'] ?? '' }}</li>
                                    <li><strong>Responsibilities:</strong>
                                        @if (is_array($exp['responsibilities']))
                                            {{ implode(', ', $exp['responsibilities']) }}
                                        @else
                                            {{ $exp['responsibilities'] ?? '' }}
                                        @endif
                                    </li>
                                </ul>
                            @endforeach
                        @endif
                    </section>
                    @endif
                    @if ($qualification)
                    <section class="education">
                        <h2 style="margin-bottom: 30px;">Education & Qualifications</h2>
                        @if (is_array($qualification))
                            @foreach ($qualification as $qual)
                                <ul class="border-left" style="margin-bottom: 20px;">
                                    <li><strong>Degree:</strong> {{ $qual['degree'] ?? '' }}</li>
                                    <li><strong>Institution:</strong> {{ $qual['institution'] ?? '' }}</li>
                                    <li><strong>Graduation Date:</strong> {{ $qual['graduation_date'] ?? '' }}</li>
                                    {{-- <li><strong>Field of Study:</strong> Specialized Field</li> --}}
                                </ul>
                            @endforeach
                        @endif
                    </section>
                    @endif
                    <!-- Availability & Preferences Section -->
                    @if (($trainingModes)|| ($countryAvailability))
                    <section class="availability-preferences">
                        <h2>Availability & Preferences</h2>
                        <div class="preferences-group">
                            {{-- Preferred Training Modes --}}
                            @if ($trainingModes)
                            <div class="preference-item" style="margin-top: 10px;">
                                <p><img class="icon1" src="{{ asset('images/setting.svg') }}" /><strong>Preferred
                                        Training Modes:</strong></p>
                                <ul class="border-left" style="padding-left: 10px;">
                                    @if (!empty($trainingModes))
                                        @foreach ($trainingModes as $mode)
                                            <li style="margin-bottom:5px;">{{ $mode ?? '' }}</li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                            @endif
                            @if ($countryAvailability)
                            {{-- Geographic Availability --}}
                            <div class="preference-item" style="margin-top: 10px;">
                                <p><img class="icon1" src="{{ asset('images/setting.svg') }}" /><strong>Geographic
                                        Availability:</strong></p>
                                <ul class="border-left" style="padding-left: 10px;">
                                    @if (!empty($countryAvailability))
                                        @foreach ($countryAvailability as $country)
                                            <li style="margin-bottom:5px;">{{ $country ?? '' }}</li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                            @endif
                        </div>
                    </section>
                    @endif
                </div>
            </main>

            <!-- Training & Courses Section -->
            @if ($courses)
            <section class="training-courses">
                <h2>Training & Courses with LPC</h2>
                <div class="courses-group">
                    @if (is_array($courses))
                        @foreach ($courses as $course)
                            <div class="course-item">
                                <p><img class="icon1"
                                        src="{{ asset('images/circle-fill.svg') }}" /><strong>{{ $course['title'] ?? '' }}
                                    </strong></p>
                                <ul class="border-left" style="padding-left:10px; ">
                                    {{-- <li>Course Title: {{ $course['title'] ?? '' }}</li> --}}
                                    <li>Duration: {{ $course['duration'] ?? '' }}</li>
                                    <li>Target Audience: {{ $course['audience'] ?? '' }}</li>
                                    <li>Mode of Delivery: {{ $course['mode_of_delivery'] ?? '' }}</li>
                                    <li>Geographical Location Delivered: {{ $course['geographic_location'] ?? '' }}
                                    </li>
                                    <li>Number of Session Delivered: {{ $course['number_of_session'] ?? '' }}</li>
                                </ul>
                            </div>
                        @endforeach
                    @endif
                </div>
            </section>
            @endif

            <!-- Publications & Speaking Engagements Section -->
            @if ($publications)
            <section class="publications-speaking">
                <h2>Publications & Speaking Engagements</h2>

                <div class="publications">
                    <h4><img class="icon1" src="{{ asset('images/circle-fill.svg') }}" />Publications</h4>
                    @if (is_array($publications))
                        <div class="courses-group">
                            @foreach ($publications as $publication)
                                <div class="course-item">
                                    <ul class="border-left"style="padding-left:10px;">
                                        <li>Title: {{ $publication['title'] ?? '' }}</li>
                                        <li>Publisher: {{ $publication['publisher'] ?? '' }}</li>
                                        <li>Publication Date: {{ $publication['date'] ?? '' }}</li>
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="speaking-engagements">
                    <h4><img class="icon1" src="{{ asset('images/circle-fill.svg') }}" />Speaking Engagements</h4>
                    @if (is_array($speaking_engagements))
                        <div class="courses-group">
                            @foreach ($speaking_engagements as $engagement)
                                <div class="course-item">
                                    <ul class="border-left"style="padding-left:10px;">
                                        <li>Title: {{ $engagement['name'] ?? '' }}</li>
                                        <li>Event Date: {{ $engagement['date'] ?? '' }}</li>
                                        <li>Topic: {{ $engagement['topic'] ?? '' }}</li>
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </section>
            @endif

            <!-- Certifications & Accreditations Section -->
            @if ($certifications)
            <section class="certifications">
                <h2>Certification & Accreditations</h2>
                <div class="courses-group">
                    @if (is_array($certifications))
                        @foreach ($certifications as $cert)
                            <div class="course-item">
                                <p>
                                    <img class="icon1" src="{{ asset('images/cert.svg') }}" alt="Award Icon">
                                    {{-- <img class="icon1" src="{{ asset('new_icon_pdf/award-icon.png') }}" alt="Award Icon"> --}}
                                    <strong>{{ $cert['title'] ?? '' }}</strong>
                                </p>
                                <ul class="border-left" style="padding-left:10px;">
                                    <li>Issuing Organization: {{ $cert['organization'] ?? '' }}</li>
                                    <li>Start/End Date: {{ $cert['date'] ?? '' }}</li>
                                </ul>
                            </div>
                        @endforeach
                    @endif
                </div>
            </section>
            @endif
            @if ($awards)
            <section class="achievements-awards">
                <h2>Professional Achievements & Awards</h2>
                <div class="courses-group">
                    @if (is_array($awards))
                        @foreach ($awards as $award)
                            <div class="course-item">
                                {{-- <p><img class="icon1" src="{{ asset('new_icon_pdf/award-icon.png') }}" alt="Award Icon"> --}}
                                <p><img class="icon1" src="{{ asset('images/award.svg') }}" alt="Award Icon">
                                    <strong>{{ $award['title'] ?? '' }}</strong>
                                </p>
                                <div class="border-left" style="padding-left:10px; margin: 5px;">
                                    <p>{{ $award['body'] ?? '' }}</p>
                                    <p>{{ $award['date'] ?? '' }}</p>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </section>
            @endif

            <div class="links">
                @if(!empty($instructor->linkedln_url))
                    <a href="{{ $instructor->linkedln_url }}" target="_blank">
                        {{-- Linkedin --}}
                        <img src="{{ asset('icon-png/linkedin.png') }}" alt="linkedin" style="height: 30px;" />
                    </a>
                @endif

                @if(!empty($instructor->facebook))
                    <a href="{{ $instructor->facebook }}" target="_blank">
                        {{-- Facebook --}}
                        <img src="{{ asset('icon-png/facebook.png') }}" alt="facebook" style="height: 30px;" />
                    </a>
                @endif

                @if(!empty($instructor->twitter))
                    <a href="{{ $instructor->twitter }}" target="_blank">
                        {{-- Twitter --}}
                        <img src="{{ asset('icon-png/twitter.png') }}" alt="Twitter" style="height: 30px;" />
                    </a>
                @endif

                @if(!empty($instructor->whatsapp))
                    <a href="{{ $instructor->whatsapp }}" target="_blank">
                        {{-- Whatsapp --}}
                        <img src="{{ asset('icon-png/whatsapp2.png') }}" alt="whatsapp" style="height: 35px;" />
                    </a>
                @endif

                @if(!empty($instructor->instagram))
                    <a href="{{ $instructor->instagram }}" target="_blank">
                        {{-- Instagram --}}
                        <img src="{{ asset('icon-png/insta2.png') }}" alt="instagram" style="height: 30px;" />
                    </a>
                @endif

                @if(!empty($instructor->portofolio_url))
                    <a href="{{ $instructor->portofolio_url }}" target="_blank">
                        {{-- Portfolio --}}
                        <img src="{{ asset('icon-png/folio.png') }}" alt="Portfolio" style="height: 30px;" />
                    </a>
                @endif
            </div>

        </div>


        {{-- footer --}}
        @include('layouts.footer1')
        {{-- /footer --}}



    </div>
</body>

</html>
