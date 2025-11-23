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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LPC System</title>
    <link rel="stylesheet" type="text/css" href="{{ public_path('css/style_inst.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ public_path('css/star-survey.css') }}">
    <!-- Bootstrap CSS -->
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
        integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

    <link rel="stylesheet" href="{{ public_path('https://fonts.googleapis.com/icon?family=Material+Icons') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<style>
    @page {
        background: white !important;
        margin-top: 30px;
        size: A4;
    }

    @page: first {
        margin-top: 0 !important;
    }

    @font-face {
        font-family: 'Amiri';
        src: url('{{ storage_path("fonts/Amiri-Regular.ttf") }}') format('truetype');
        font-weight: normal;
        font-style: normal;
    }
    body {
        font-family: 'Amiri', DejaVu Sans, sans-serif;
        background: white !important;
    }

    body, p, span, div, table, th, td {
        direction: {{ $direction }};
        unicode-bidi: embed;
        text-align: {{ $direction === 'rtl' ? 'right' : 'left' }};
        font-family: 'DejaVu Sans', Arial, sans-serif;
    }
    main {
        background: white !important;
    }

    /* .page-number:before {
        position: fixed;
        bottom: 0;
        left: 10px;
        content: "Page " counter(page);
    } */

    /* .page-total:before {
            position: fixed;
            bottom:0;
            left:40px;
            content:counter(pages);
        } */
</style>

<body dir="{{ $direction }}" style="text-align: {{ $direction === 'rtl' ? 'right' : 'left' }};">

    {{-- <span class="page-number"></span> --}}
    <main>

        <section class="hero-section">
            <div class="curv-box2"></div>
            <img src="{{ public_path('whiteLogo.png') }}" alt="logo" class="logo2" />
        </section>
        <section class="course-section">
            <div id="submittedMessage" style="display: none;">
                <h3 class="label"> Survey submitted successfully! </h3>
            </div>
            {{-- form --}}
            <div class="container">
                <strong style="margin-top: 15px; color: black;">{{ $form->title }}</strong>
                @if ($form->type == 'pre_course')
                    @php
                        $class = \Modules\Lms\Models\Classe::find($form->classe_id)->first();
                        $courseName = $class ? $class->course->name : null;
                    @endphp
                    @if ($courseName)
                        <strong style="margin-top: 5px; display: block; color: black;">Course:
                            {{ $courseName }}</strong>
                    @endif
                @endif
                <p style="margin-top: 15px;margin-bottom: 10px;color: black;">
                    {{ $form->description }}
                </p>
                <div id="submittedMessage" style="display: none;">
                    <h3 class="label">Survey submitted successfully!</h3>
                </div>

                <form id="surveyForm" method="POST">
                    @csrf

                    <input type="hidden" name="form_id" value="{{ $form->id }}">
                    {{-- border: 2px solid #D69900; --}}
                    <div style="margin-bottom: 20px; padding: 20px;  background-color: white; border-radius: 10px;">
                        {{-- info --}}
                        <style>
                            table td {
                                /* padding: 10px; */
                            }

                            .form-control {
                                margin-left: auto;
                                margin-right: auto;
                                display: block;
                                width: 90%;
                            }

                            .custom-label {
                                color: black;
                                font-size: 16px;
                                font-weight: normal;
                            }

                            .custom-input {
                                margin-top: 15px;
                                margin-bottom: 20px;
                                color: black;
                                font-size: 16px;
                                font-weight: normal;
                                height: 25PX;
                                /* border-color: #012951; */
                            }

                            .custom-input2 {
                                margin-top: 15px;
                                color: black;
                                font-size: 16px;
                                /* border-color: #012951; */
                                font-weight: normal;
                            }
                        </style>

                        @auth
                            <table width="100%">
                                <tr>
                                    <td width="48%">
                                        <label for="name" class="custom-label">Name</label>
                                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                        <input type="text" name="name" class="form-control custom-input"
                                            value="{{ auth()->user()->name }}" readonly>
                                    </td>
                                    <td width="4%"></td>
                                    <td width="48%">
                                        <label for="email" class="custom-label">Email</label>
                                        <input type="email" name="email" class="form-control custom-input"
                                            value="{{ auth()->user()->email }}" readonly>
                                    </td>
                                </tr>
                            </table>
                        @else
                            <table width="100%">
                                <tr>
                                    <td width="48%">
                                        <label for="name" class="custom-label">First Name <span
                                                class="required-star">*</span></label>
                                        <input type="text" name="info[name]" class="form-control custom-input" required>
                                    </td>
                                    <td width="4%"></td>
                                    <td width="48%">
                                        <label for="middle_name" class="custom-label">Middle Name</label>
                                        <input type="text" name="info[middle_name]" class="form-control custom-input">
                                    </td>
                                </tr>
                                <tr>
                                    <td width="48%">
                                        <label for="last_name" class="custom-label">Last Name</label>
                                        <input type="text" name="info[last_name]" class="form-control custom-input"
                                            required>
                                    </td>
                                    <td width="4%"></td>
                                    <td width="48%">
                                        <label for="phone" class="custom-label">Phone</label>
                                        <input type="text" name="info[phone]" class="form-control custom-input" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="48%">
                                        <label for="email" class="custom-label">Email <span
                                                class="required-star">*</span></label>
                                        <input type="email" name="email" class="form-control custom-input" required>
                                    </td>
                                    <td width="4%"></td>
                                    <td width="48%">
                                        <label for="company" class="custom-label">Company</label>
                                        <input type="text" name="info[company_id]" class="form-control custom-input"
                                            required>
                                        {{-- <select name="info[company_id]" class="form-control">
                                            <option value="" selected>Select a company</option>
                                            <option value="null">None</option>
                                            @foreach ($companies as $company)
                                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                                            @endforeach
                                        </select> --}}
                                    </td>
                                </tr>
                            </table>
                        @endauth

                        {{-- /info --}}
                        {{-- survey --}}
                        <hr style="margin: 25px;">

                        @foreach ($form->questions as $question)
                            <div class="form-group" style="page-break-inside: avoid;">
                                <label for="question_{{ $question->id }}" style="font-size: 1.2em; color: #D69900;">
                                    <strong>
                                        {{ $direction === 'rtl' ? 'سؤال ' : 'Question ' }} {{ $loop->iteration }}
                                    </strong>
                                </label><br>
                                <strong style="color: #012951; margin-top:15px;">{{ $question->question }}</strong>
                                @if ($question->is_required)
                                    <span class="required-star">*</span>
                                @endif
                                <br>
                                <input type="hidden" name="answers[{{ $question->id }}][question_id]"
                                    value="{{ $question->id }}">

                                @switch($question->type)
                                    @case(\App\Enums\QuestionEnum::one_choice)
                                        <table style="width: 100%; border-collapse: collapse;">
                                            <tr>
                                                @foreach (json_decode($question->options, true) as $option)
                                                    <td
                                                        style="padding: 5px; text-align: left; width: {{ 100 / count(json_decode($question->options, true)) }}%;">
                                                        <input type="radio" name="answers[{{ $question->id }}][answer]"
                                                            value="{{ $option['title'] }}">
                                                        <label class="custom-label">{{ $option['title'] }}</label>
                                                    </td>
                                                @endforeach
                                            </tr>
                                        </table>
                                    @break

                                    @case(\App\Enums\QuestionEnum::multi_choice)
                                        <table style="width: 100%; border-collapse: collapse;">
                                            <tr>
                                                @foreach (json_decode($question->options, true) as $option)
                                                    <td
                                                        style="padding: 5px; text-align: left; width: {{ 100 / count(json_decode($question->options, true)) }}%;">
                                                        <input type="checkbox" name="answers[{{ $question->id }}][answer][]"
                                                            value="{{ $option['title'] }}">
                                                        <label class="custom-label">{{ $option['title'] }}</label>
                                                    </td>
                                                @endforeach
                                            </tr>
                                        </table>
                                    @break

                                    @case(\App\Enums\QuestionEnum::text)
                                        <textarea name="answers[{{ $question->id }}][answer]" class="form-control custom-input2" rows="5"
                                            columns="5"></textarea>
                                    @break

                                    @case(\App\Enums\QuestionEnum::rating)
                                        <table>
                                            <tr>
                                                @for ($i = 5; $i >= 1; $i--)
                                                    <td style="padding-right: 10px; padding-top:15px;">
                                                        <img class="icon2"
                                                            src="{{ public_path('images/star-e-svgrepo-com.svg') }}"
                                                            style="vertical-align: middle; width: 16px;" />
                                                        {{-- <input type="radio" id="star{{ $i }}_{{ $question->id }}" name="answers[{{ $question->id }}][answer]" value="{{ $i }}">
                                                    <label for="star{{ $i }}_{{ $question->id }}" title="{{ $i }} stars">&#9733;</label> --}}
                                                    </td>
                                                @endfor
                                            </tr>
                                        </table>
                                    @break

                                    @case(\App\Enums\QuestionEnum::missing_word)
                                        @php
                                            $sentence = str_replace(
                                                '__',
                                                '<input type="text" name="answers[' .
                                                    $question->id .
                                                    '][answer]" class="form-control d-inline-block" style="width: 150px;" placeholder="Fill in the word">',
                                                $question->options,
                                            );
                                        @endphp
                                        <p>{!! $sentence !!}</p>
                                    @break

                                    @default
                                @endswitch

                                @error('answers.' . $question->id . '.answer')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        @endforeach
                    </div>
                    {{-- /survey --}}
                    {{-- <div style="text-align: center;">
            <button type="submit" class="btn" style="background-color: #012951; font-weight: bold; color: #fff; width: 200px; padding: 10px 20px;">
                Submit
            </button>
          </div> --}}
                </form>
            </div>
            {{-- /form --}}

    </main>
    <div style="page-break-after:always;"></div>
    <style>
        .footer {
            /* margin-top:61% !important; */
            /* display: none;
            position: fixed; */
            bottom: 0;
            width: 100%;
            background-color: #0b2a4a;
            color: #ffffff;
            padding: 40px 0;
            margin-top: 15px;
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

    <div class="last">
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
