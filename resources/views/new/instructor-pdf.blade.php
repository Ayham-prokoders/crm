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
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Profile</title>
    <link rel="stylesheet" href="{{ public_path('css/new-style/profile-pdf.css') }}">
</head>

<style>
    @page {
        background: white !important;
        /* margin-top:30px; */
        /* size: A4; */
        margin: 0 !important;
    }

    /* @page:first{
        padding-top:0 !important;
    } */
    body {
        background: white !important;
    }

    main {
        background: white !important;
    }

    /* .page-number:before {
            position: fixed;
            bottom:0;
            left:10px;
            content: "Page " counter(page);
        } */
    /* .page-total:before {
            position: fixed;
            bottom:0;
            left:40px;
            content:counter(pages);
        } */
</style>

<body style="margin:0 !important;">
    {{-- <span class="page-number"></span> --}}
    <table class="header" width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td class="logo" style="width: 50%;">
                <img src="{{ public_path('whiteLogo.png') }}" alt="LPC Logo">
            </td>
            <td class="submission-date" style="width: 50%; text-align: right;">
                <p>Date Of Submission : {{ $instructor->date_of_submission }}</p>
            </td>
        </tr>
    </table>
    <main>
        <!-- Header Section -->

        <div class="container1">
            <!-- Instructor Profile Section -->
            <table class="profile" width="100%" cellpadding="10" cellspacing="0">
                <tr>
                    <td class="profile-image" style="width: 20%;">
                        <img src="{{ $user->image ? public_path($user->image) : public_path('images/default-profile.png') }}"
                            alt="Instructor">
                    </td>
                    <td class="profile-info" style="width: 60%;">
                        <h1>{{ $user->name }} {{ $user->last_name }}</h1>
                        {{-- <p>Passionate Social Studies Educator | Mentorship | Cross-Culture Engagement</p> --}}
                        <table class="contact-info" style="width: 100%;">
                            <tr>
                                <td style="width: 40%; padding-top: 15px;">
                                    <img src="{{ public_path('images/email-svgrepo-com.svg') }}" alt="Email"
                                        width="16px">
                                    {{ $user->email }}
                                </td>
                                <td style="width: 20%; padding-top: 15px;">
                                    {{-- @php
                                    $location = \Modules\Lms\Models\City::find($instructor->location);
                                    @endphp --}}
                                @if($instructor->city)
                                    @if($instructor->city->name)
                                        <img src="{{ public_path('images/location-pin-svgrepo-com.svg') }}" alt="Location" width="16px">
                                        {{ $instructor->city->name }}
                                    @endif
                                @endif
                                </td>
                                <td style="width: 20%; padding-top: 15px;">
                                    <img src="{{ public_path('images/phone.svg') }}" alt="Phone" width="8px">
                                    {{ $user->phone }}
                                </td>
                            </tr>
                            <!-- Row for rating -->
                            <tr>
                                <td colspan="3" style="padding-top: 15px;">
                                    <span style="padding-right: 10px; vertical-align: middle;">Rated</span>
                                    <span class="stars" style="vertical-align: middle;">
                                        @php
                                            $fullStars = floor($instructor->rating);
                                            $halfStar = $instructor->rating - $fullStars >= 0.5;
                                        @endphp

                                        <!-- Full stars -->
                                        @for ($i = 0; $i < $fullStars; $i++)
                                            <img class="icon2" src="{{ public_path('images/star-svgrepo-com.svg') }}"
                                                style="vertical-align: middle; width: 16px;" />
                                        @endfor

                                        <!-- Half star if applicable -->
                                        @if ($halfStar)
                                            <img class="icon2"
                                                src="{{ public_path('images/star-half-svgrepo-com.svg') }}"
                                                style="vertical-align: middle; width: 16px;" />
                                        @endif

                                        <!-- Empty stars to complete the 5 stars (optional) -->
                                        {{-- @for ($i = 0; $i < 5 - $fullStars - ($halfStar ? 1 : 0); $i++)
                                        <img class="icon2" src="{{ public_path('images/star-e-svgrepo-com.svg') }}" style="vertical-align: middle; width: 16px;" />
                                    @endfor --}}
                                    </span>
                                </td>
                            </tr>
                        </table>

                    </td>

                </tr>
            </table>



            <!-- Main Content Section -->
            {{-- <div class="left-column"> --}}
            @if (!is_null($instructor->professional_summary))
                <section class="summary">
                    <h2>Personal Summary</h2>
                    <p>{!! $instructor->professional_summary !!}</p>
                </section>
            @endif

            @if (($instructor->topics && $instructor->topics->count() > 0) || (is_array($languages) && count($languages) > 0))
                <section class="expertise" style="margin-bottom: 20px;">

                    <h2>Areas of Expertise</h2>
                    <!-- Specialized Topics -->
                    {{-- @if (is_array($specilized_topics))
                    <div class="clearfix" style="width: 48%; float: left; margin-right: 4%; box-sizing: border-box;">
                        <h4>Specialized Topics</h4>
                        <table style="width: 100%; border-collapse: collapse; margin-left: 10px; margin-bottom: 10px;">
                            @foreach ($specilized_topics as $topic)
                                <tr>
                                    <td class="border-left" style="vertical-align: top;">
                                        {{ $topic ?? '' }}
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                @endif --}}
                    @if ($instructor->topics && $instructor->topics->count() > 0)
                        <div class="clearfix"
                            style="width: 48%; float: left; margin-right: 4%; box-sizing: border-box;">
                            <h4>Specialized Topics</h4>
                            <table
                                style="width: 100%; border-collapse: collapse; margin-left: 10px; margin-bottom: 10px;">
                                @foreach ($instructor->topics as $topic)
                                    <tr>
                                        <td class="border-left" style="vertical-align: top;">
                                            {{ $topic->title}}
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    @endif
                    <!-- Languages -->
                    @if (!is_null($languages))
                        @if (is_array($languages))
                            <div class="clearfix" style="width: 48%; float: left; box-sizing: border-box;">
                                <h4>Languages</h4>
                                <table style="width: 100%; border-collapse: collapse; margin-left: 10px;">
                                    @foreach ($languages as $language)
                                        <tr>
                                            <td class="border-left" style="vertical-align: top;">
                                                {{ $language['title'] ?? '' }}:
                                                <strong>{{ $language['level'] ?? '' }}</strong>
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        @endif
                    @endif
                    <!-- Clear floats -->
                    <div style="clear: both;"></div>
                </section>
            @endif

            @if (is_array($testimonials) && count($testimonials) > 0)
                <section class="testimonial-reference">
                    <h2 style="margin-bottom: 20px;">Testimonial/Reference</h2>
                    <div class="courses-group clearfix" style="width: 100%; overflow: hidden;">
                        @if (is_array($testimonials))
                            @foreach ($testimonials as $index => $testimonial)
                                <div class="reference-item"
                                    style="width: 48%; float: left; margin-right: 4%; box-sizing: border-box; page-break-inside: avoid;">
                                    <ul class="reference-details border-left"
                                        style="margin-left: 10px; margin-bottom: 30px; padding-left: 10px;">
                                        @if (!is_null($testimonial['name']))
                                            <li><strong>Name:</strong> {{ $testimonial['name'] }}</li>
                                        @endif
                                        @if (!is_null($testimonial['position']))
                                            <li><strong>Position:</strong> {{ $testimonial['position'] }}</li>
                                        @endif
                                        @if (!is_null($testimonial['organization']))
                                            <li><strong>Organization:</strong>
                                                {{ $testimonial['organization'] ?? 'Organization' }}</li>
                                        @endif
                                        @if (!is_null($testimonial['contact_info']))
                                            <li><strong>Contact Information:</strong>
                                                {{ $testimonial['contact_info'] }}
                                            </li>
                                        @endif
                                        @if (!is_null($testimonial['content']))
                                            <li><strong>Testimonial:</strong> {{ $testimonial['content'] }}</li>
                                        @endif
                                    </ul>
                                </div>

                                @if ($index % 2 == 1)
                                    <div style="clear: both;"></div> <!-- Clear floats after every two items -->
                                @endif
                            @endforeach
                        @endif
                    </div>
                </section>
            @endif
            {{-- </div> --}}
            {{-- <div class="right-column"> --}}
            @if (is_array($experience) && count($experience) > 0)
                <section class="experience">
                    <h2 style="margin-bottom: 20px;">Professional Experience</h2>
                    @if (is_array($experience))
                        @foreach ($experience as $exp)
                            <ul class="border-left" style="margin-bottom: 30px;page-break-inside: avoid;">
                                @if (!is_null($exp['job_title']))
                                    <li><strong>Current Position:</strong>{{ $exp['job_title'] }}</li>
                                @endif
                                @if (!is_null($exp['organization']))
                                    <li><strong>Organization:</strong>{{ $exp['organization'] }}</li>
                                @endif
                                @if (!is_null($exp['start_date']))
                                    <li><strong>Start Date:</strong>{{ $exp['start_date'] }}</li>
                                @endif
                                @if (!is_null($exp['responsibilities']))
                                    <li><strong>Responsibilities:</strong>
                                        @if (is_array($exp['responsibilities']))
                                            {{ implode(', ', $exp['responsibilities']) }}
                                        @else
                                            {{ $exp['responsibilities'] }}
                                        @endif
                                    </li>
                                @endif
                            </ul>
                        @endforeach
                    @endif
                </section>
            @endif
            @if (is_array($qualification) && count($qualification) > 0)
                <section class="education">
                    <h2 style="margin-bottom: 20px;">Education & Qualifications</h2>
                    @if (is_array($qualification))
                        <div class="clearfix" style="width: 100%; overflow: hidden;">
                            @foreach ($qualification as $index => $qual)
                                <ul class="border-left"
                                    style="width: 48%; float: left; margin-right: 4%; margin-bottom: 30px; box-sizing: border-box; page-break-inside: avoid;">
                                    @if (!is_null($qual['degree']))
                                        <li><strong>Degree:</strong> {{ $qual['degree'] }}</li>
                                    @endif
                                    @if (!is_null($qual['institution']))
                                        <li><strong>Institution:</strong> {{ $qual['institution'] }}</li>
                                    @endif
                                    @if (!is_null($qual['graduation_date']))
                                        <li><strong>Graduation Date:</strong> {{ $qual['graduation_date'] }}</li>
                                    @endif
                                </ul>

                                @if ($index % 2 == 1)
                                    <div style="clear: both;"></div> <!-- Clear floats after every two columns -->
                                @endif
                            @endforeach
                        </div>
                    @endif
                </section>
            @endif
            <!-- Availability & Preferences Section -->
            @if (
                (is_array($trainingModes) && count($trainingModes) > 0) ||
                    (is_array($countryAvailability) && count($countryAvailability) > 0))
                <section class="availability-preferences"style="text-align: left;">
                    <h2>Availability & Preferences</h2>
                    <div class="preferences-group clearfix" style="width: 100%; overflow: hidden;">
                        {{-- Preferred Training Modes --}}
                        @if (!is_null($trainingModes))
                            <div class="preference-item"
                                style="width: 48%; float: left; margin-right: 4%; box-sizing: border-box;">
                                <p style="page-break-after: avoid;">
                                    <img class="icon1" src="{{ public_path('images/setting.svg') }}" />
                                    <strong>Preferred Training Modes:</strong>
                                </p>
                                <ul class="border-left" style="padding-left: 10px;">
                                    @if (!empty($trainingModes))
                                        @foreach ($trainingModes as $mode)
                                            <li>{{ $mode }}</li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        @endif
                        @if (!is_null($countryAvailability))
                            {{-- Geographic Availability --}}
                            <div class="preference-item" style="width: 48%; float: left; box-sizing: border-box;">
                                <p style="page-break-after: avoid;">
                                    <img class="icon1" src="{{ public_path('images/setting.svg') }}" />
                                    <strong>Geographic Availability:</strong>
                                </p>
                                <ul class="border-left" style="padding-left: 10px;">
                                    @if (!empty($countryAvailability))
                                        @foreach ($countryAvailability as $country)
                                            <li>{{ $country ?? '' }}</li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        @endif
                        <div style="clear: both;"></div> <!-- Clear floats -->
                    </div>
                </section>
            @endif
            {{-- </div> --}}
            <!-- Training & Courses Section -->
            @if (is_array($courses) && count($courses) > 0)
                <section class="training-courses">
                    <h2>Training & Courses with LPC</h2>
                    <div class="courses-group clearfix" style="width: 100%; overflow: hidden;">
                        @if (is_array($courses))
                            @foreach ($courses as $index => $course)
                                <div class="course-item"
                                    style="width: 48%; float: left; margin-right: 4%; page-break-inside: avoid; box-sizing: border-box;">
                                    <p>
                                        <img class="icon1" src="{{ public_path('images/circle-fill.svg') }}" />
                                        <strong>{{ $course['title'] }}</strong>
                                    </p>
                                    <ul class="border-left" style="padding-left: 10px;">
                                        @if (!is_null($course['duration']))
                                            <li>Duration: {{ $course['duration'] }}</li>
                                        @endif
                                        @if (!is_null($course['audience']))
                                            <li>Target Audience: {{ $course['audience'] }}</li>
                                        @endif
                                        @if (!is_null($course['mode_of_delivery']))
                                            <li>Mode of Delivery: {{ $course['mode_of_delivery'] }}</li>
                                        @endif
                                        @if (!is_null($course['geographic_location']))
                                            <li>Geographical Location Delivered: {{ $course['geographic_location'] }}
                                            </li>
                                        @endif
                                        @if (!is_null($course['number_of_session']))
                                            <li>Number of Sessions Delivered: {{ $course['number_of_session'] }}</li>
                                        @endif
                                    </ul>
                                </div>
                                @if ($index % 2 == 1)
                                    <div style="clear: both;"></div> <!-- Clear floats after every two items -->
                                @endif
                            @endforeach
                        @endif
                    </div>
                </section>
            @endif

            <!-- Courses Section -->
            {{-- <section class="courses">
                <h2>Courses</h2>
                <ul>
                    <li>Title: Course Title</li>
                    <li>Location: Location</li>
                    <li>Category: Category</li>
                    <li>Date: Date</li>
                </ul>
            </section> --}}

            <!-- Publications & Speaking Engagements Section -->
            @if (
                (is_array($publications) && count($publications) > 0) ||
                    (is_array($speaking_engagements) && count($speaking_engagements) > 0))
                <section class="publications-speaking">
                    <h2>Publications & Speaking Engagements</h2>
                    @if (!is_null($publications))
                        <div class="publications">
                            <h4 style="page-break-after: avoid;"><img class="icon1"
                                    src="{{ public_path('images/circle-fill.svg') }}" />Publications</h4>
                            @if (is_array($publications))
                                <div class="courses-group clearfix">
                                    @foreach ($publications as $publication)
                                        <div class="course-item"
                                            style="width: 48%; float: left; margin-right: 4%; box-sizing: border-box;">
                                            <ul class="border-left"
                                                style="padding-left: 10px; page-break-inside: avoid;">
                                                @if (!is_null($publication['title']))
                                                    <li>Title: {{ $publication['title'] }}</li>
                                                @endif
                                                @if (!is_null($publication['publisher']))
                                                    <li>Publisher: {{ $publication['publisher'] }}</li>
                                                @endif
                                                @if (!is_null($publication['date']))
                                                    <li>Publication Date: {{ $publication['date'] }}</li>
                                                @endif
                                            </ul>
                                        </div>

                                        <!-- Clear float after every two items -->
                                        @if ($loop->index % 2 == 1)
                                            <div style="clear: both;"></div>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endif
                    @if (!is_null($speaking_engagements))
                        <div class="speaking-engagements">
                            <h4 style="page-break-after: avoid;"><img class="icon1"
                                    src="{{ public_path('images/circle-fill.svg') }}" />Speaking Engagements</h4>
                            @if (is_array($speaking_engagements))
                                <div class="courses-group clearfix">
                                    @foreach ($speaking_engagements as $engagement)
                                        <div class="course-item"
                                            style="width: 48%; float: left; margin-right: 4%; box-sizing: border-box;">
                                            <ul class="border-left"
                                                style="padding-left: 10px; page-break-inside: avoid;">
                                                @if (!is_null($engagement['name']))
                                                    <li>Title: {{ $engagement['name'] }}</li>
                                                @endif
                                                @if (!is_null($engagement['date']))
                                                    <li>Event Date: {{ $engagement['date'] }}</li>
                                                @endif
                                                @if (!is_null($engagement['topic']))
                                                    <li>Topic: {{ $engagement['topic'] }}</li>
                                                @endif
                                            </ul>
                                        </div>

                                        <!-- Clear float after every two items -->
                                        @if ($loop->index % 2 == 1)
                                            <div style="clear: both;"></div>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endif
                </section>
            @endif
            <!-- Certifications & Accreditations Section -->
            @if (is_array($certifications) && count($certifications) > 0)
                <section class="certifications">
                    <h2 style="page-break-after: avoid;">Certification & Accreditations</h2>
                    <div class="courses-group clearfix" style="width: 100%; overflow: hidden;">
                        @if (is_array($certifications))
                            @foreach ($certifications as $index => $cert)
                                <div class="course-item"
                                    style="width: 48%; float: left; margin-right: 4%; box-sizing: border-box; page-break-inside: avoid;">
                                    <p><img class="icon1" src="{{ public_path('images/cert.svg') }}"
                                            alt="Award Icon">
                                        <strong>{{ $cert['title'] }}</strong>
                                    </p>
                                    <ul class="border-left" style="padding-left:10px;">
                                        @if (!is_null($cert['organization']))
                                            <li>Issuing Organization: {{ $cert['organization'] }}</li>
                                        @endif
                                        @if (!is_null($cert['date']))
                                            <li>Start/End Date: {{ $cert['date'] }}</li>
                                        @endif
                                    </ul>
                                </div>

                                @if ($index % 2 == 1)
                                    <div style="clear: both;"></div> <!-- Clear floats after every two items -->
                                @endif
                            @endforeach
                        @endif
                    </div>
                </section>
            @endif
            @if (is_array($awards) && count($awards) > 0)
                <section class="achievements-awards">
                    <h2>Professional Achievements & Awards</h2>
                    <div class="courses-group clearfix" style="width: 100%; overflow: hidden;">
                        @if (is_array($awards))
                            @foreach ($awards as $index => $award)
                                <div class="course-item"
                                    style="width: 48%; float: left; margin-right: 4%; box-sizing: border-box; page-break-inside: avoid;">
                                    <p><img class="icon1" src="{{ public_path('images/award.svg') }}"
                                            alt="Award Icon">
                                        <strong>{{ $award['title'] }}</strong>
                                    </p>
                                    <div class="border-left" style="padding-left:10px; margin: 5px;">
                                        <p>{{ $award['body'] ?? '' }}</p>
                                        <p>{{ $award['date'] ?? '' }}</p>
                                    </div>
                                </div>

                                @if ($index % 2 == 1)
                                    <div style="clear: both;"></div> <!-- Clear floats after every two items -->
                                @endif
                            @endforeach
                        @endif
                    </div>
                </section>
            @endif
        </div>
    </main>
    {{-- </div> --}}
    <style>
        .footer {
            /* display: none;
            position: fixed; */
            bottom: 0;
            width: 100%;
            background-color: #0b2a4a;
            color: #ffffff;
            padding: 40px 0;
            margin-top: 15px;
            /* margin-bottom:20px; */
            font-family: Arial, sans-serif;
        }


        /* .last .footer {
            display: block;
        } */

        .footer .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }

        .logo-section {
            display: inline-block;
            vertical-align: center;
            margin-top: 50%;
            width: 100%;
        }

        .logof {
            width: 80%;
            height: auto;
        }

        .location-section {
            display: inline-block;
            vertical-align: top;
            width: 100%;
        }

        .location-table {
            width: 100%;
            border-collapse: collapse;
        }

        .location-table td {
            width: 50%;
            vertical-align: top;
            padding: 10px;
        }

        .location h3 {
            font-size: 16px;
            margin-bottom: 10px;
            color: rgb(255, 166, 0);
        }

        .location p {
            font-size: 14px;
            margin: 5px 0;
        }

        .footer-middle {
            text-align: left;
        }

        .get-in-touch {
            margin-top: 10px;
            margin-bottom: 20px;
        }

        .footer-icon {
            display: inline-block;
            margin-right: 5px;
        }

        .footer-icon img {
            width: 20px;
            height: auto;
        }

        .footer-bottom {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }

        .footer-bottom ul {
            list-style: none;
            padding: 0;
            margin: 10px 0 0;
            display: inline-block;
        }

        .footer-bottom ul li {
            display: inline;
            margin: 0 10px;
        }

        .footer-bottom ul li a {
            color: #ffffff;
            text-decoration: none;
        }

        .footer-bottom ul li a:hover {
            text-decoration: underline;
        }
    </style>

    <div class="links" style="text-align: center; margin-top:20px;">
        @if (!is_null($instructor->linkedln_url))
            <a href="{{ $instructor->linkedln_url }}" target="_blank"
                style="display: inline-block; text-decoration: none; padding: 5px 10px; margin-right: 15px;">
                {{-- linkedin --}}
                <img src="{{ public_path('icon-png/linkedin.png') }}" alt="LinkedIn" style="height: 30px;" />
            </a>
        @endif
        @if (!is_null($instructor->facebook))
            <a href="{{ $instructor->facebook }}" target="_blank"
                style="display: inline-block; text-decoration: none; padding: 5px 10px; margin-right: 15px;">
                <img src="{{ public_path('icon-png/facebook.png') }}" alt="Facebook" style="height: 30px;" />
                {{-- facebook --}}
            </a>
        @endif
        @if (!is_null($instructor->whatsapp))
            <a href="{{ $instructor->whatsapp }}" target="_blank"
                style="display: inline-block; text-decoration: none; padding: 5px 10px; margin-right: 15px;">
                <img src="{{ public_path('icon-png/whatsapp2.png') }}" alt="Whatsapp" style="height: 30px;" />
                {{-- facebook --}}
            </a>
        @endif
        @if (!is_null($instructor->twitter))
            <a href="{{ $instructor->twitter }}" target="_blank"
                style="display: inline-block; text-decoration: none; padding: 5px 10px; margin-right: 15px;">
                <img src="{{ public_path('icon-png/twitter.png') }}" alt="Twitter" style="height: 30px;" />
                {{-- facebook --}}
            </a>
        @endif
        @if (!is_null($instructor->portofolio_url))
            <a href="{{ $instructor->portofolio_url }}" target="_blank"
                style="display: inline-block; text-decoration: none; padding: 5px 10px;">
                {{-- Portfolio --}}
                <img src="{{ public_path('icon-png/folio.png') }}" alt="portofolio" style="height: 30px;" />
            </a>
        @endif
        @if (!is_null($instructor->instagram))
            <a href="{{ $instructor->instagram }}" target="_blank"
                style="display: inline-block; text-decoration: none; padding: 5px 10px;">
                {{-- Portfolio --}}
                <img src="{{ public_path('icon-png/insta2.png') }}" alt="instagram" style="height: 30px;" />
            </a>
        @endif
    </div>
    {{-- <div style="page-break-after:always;"></div> --}}

    <div class="last" style="margin-top:15px; bottom:100px;">
        <footer class="footer" style="page-break-inside: avoid;">
            <div class="container">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="width: 30%; vertical-align: top; text-align: center;">
                            <!-- Logo Section -->
                            <div class="logo-section">
                                <img src="{{ public_path('whiteLogo.png') }}" alt="LPC Logo" class="logof">
                            </div>
                        </td>
                        <td style="width: 70%; vertical-align: top;">
                            <!-- Location Section -->
                            <div class="location-section">
                                <table class="location-table">
                                    <tr>
                                        <td>
                                            <div class="location">
                                                <h3>LONDON</h3>
                                                {!! $contact_message1 !!}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="location">
                                                <h3>DUBAI</h3>
                                                {!! $contact_message2 !!}
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="location">
                                                <h3>KUALA LUMPUR</h3>
                                                {!! $contact_message3 !!}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="location">
                                                <h3>BARCELONA</h3>
                                                {!! $contact_message4 !!}
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <!-- Get in Touch Section -->
                            <div class="footer-middle">
                                <div class="get-in-touch-section">
                                    <div class="label" style="color: rgb(255, 166, 0); margin-bottom:15px;">Get in
                                        touch
                                    </div>
                                    <div class="get-in-touch">
                                        <div class="footer-icon">
                                            <a href="{{ $_footer_whatsapp }}" target="_blank"
                                                style="text-decoration: none;">
                                                <img src="{{ public_path('new_icon_pdf/youtube.png') }}"
                                                    alt="YouTube">
                                            </a>
                                        </div>
                                        <div class="footer-icon">
                                            <a href="{{ $_footer_facebook }}" target="_blank"
                                                style="text-decoration: none;">
                                                <img src="{{ public_path('new_icon_pdf/facebook.png') }}"
                                                    alt="Facebook">
                                            </a>
                                        </div>
                                        <div class="footer-icon">
                                            <a href="{{ $_footer_twitter }}" target="_blank"
                                                style="text-decoration: none;">
                                                <img src="{{ public_path('new_icon_pdf/x.png') }}" alt="Twitter">
                                            </a>
                                        </div>
                                        <div class="footer-icon">
                                            <a href="{{ $_footer_linkedin }}" target="_blank"
                                                style="text-decoration: none;">
                                                <img src="{{ public_path('new_icon_pdf/linked-in.png') }}"
                                                    alt="LinkedIn">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <hr style="border: 1px solid #ccc; margin: 10px 0;" />
                            <!-- Copyright + Links -->
                            <div class="footer-bottom">
                                <p>Copyright © 2024 lpcentre.com All Rights Reserved.</p>
                                <ul>
                                    <li><a href="{{ $website_url }}/contact">Contact</a></li>
                                    <li><a href="{{ $website_url }}/terms-and-conditions">Terms and Conditions</a>
                                    </li>
                                    <li><a href="{{ $website_url }}/privacy-policy">Privacy Policy</a></li>
                                    <li><a href="{{ $website_url }}/quality-policy">Quality Policy</a></li>
                                    <li><a href="{{ $website_url }}/become-an-instructor">Become an instructor</a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </footer>
    </div>
</body>


</html>
