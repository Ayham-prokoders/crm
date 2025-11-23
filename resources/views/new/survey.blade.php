<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LPC System</title>
    <script src="https://www.google.com/recaptcha/api.js?render={{ env('GOOGLE_RECAPTCHA_KEY') }}"></script>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('ico.ico') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="/css/css" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
        integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:wght@300;400;600;700;900&display=swap"
        rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@300;400;700;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/css/icon') }}">

    <!--  bootstrap table -->
    <link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.18.0/dist/bootstrap-table.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.css"
        integrity="sha512-UTNP5BXLIptsaj5WdKFrkFov94lDx+eBvbKyoe1YAfjeRPC+gT5kyZ10kOHCfNZqEui1sxmqvodNUx3KbuYI/A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="/css/icon-css/all.css" />
    {{-- <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet"> --}}
    <!-- Custom Styles -->
    {{-- <link rel="stylesheet" href="{{ asset('/vendor/slick/slick.css') }}" />
    <link rel="stylesheet" href="{{ asset('/vendor/slick/slick-theme.css') }}" /> --}}
    <link rel="stylesheet" href="{{ asset('/css/style.css?v=' . time()) }}">
    <link rel="stylesheet" href="{{ asset('/css/nav.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/star-survey.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/mega-menu.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/footer.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('/css/reg-form.css') }}"> --}}
    {{-- <link rel="stylesheet" href="{{ asset('/css/brochure-form.css') }}"> --}}
    {{-- <link rel="stylesheet" href="{{ asset('/css/contact-form.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('/css/overriders.css') }}" />
    {{-- <script src="{{ asset('vendor/notiflix/notiflix-report-aio-3.2.5.min.js') }}"></script> --}}

</head>

<body style="background: white;">
    <main>

        {{-- navbar --}}

        @include('layouts.nav')
        {{-- /navbar --}}


        {{-- image --}}
        <div class="top-banner"
            style="background-image: url({{ $form->image != null ? asset($form->image) : asset('/images/survey-img.jpg') }})">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div>
                            <h1> {{ $form->title }}</h1>

                        </div>
                    </div>
                </div>
            </div>
        </div>



        {{-- /image --}}

        {{-- form --}}
        <div class="container">

            @if ($form->type == 'pre_course')
                @php
                    $class = \Modules\Lms\Models\Classe::find($form->classe_id)->first();
                    $courseName = $class ? $class->course->name : null;
                @endphp
                @if ($courseName)
                    <strong style="margin-top: 15px;">Course: {{ $courseName }}</strong>
                @endif
            @endif
            <p style="margin-top: 15px;">{{ $form->description }}</p>
            <div id="submittedMessage" style="display: none;">
                <h3 class="label">Survey submitted successfully!</h3>
            </div>

            <form id="surveyForm" method="POST">
                @csrf

                <input type="hidden" name="form_id" value="{{ $form->id }}">
                <div
                    style="margin-bottom:20px;align-items:center;background: white; border: 2px solid #D69900; padding: 20px; border-radius: 10px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);">
                    {{-- info --}}
                    @if($user)
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="name" style="font-size: 1.1em;">Name</label>
                                <input type="hidden" name="user_id" value="{{ $user->id ?? '' }}">
                                <input type="text" name="name" class="form-control"
                                    value="{{ $user->name ?? '' }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="email" style="font-size: 1.1em;">Email</label>
                                <input type="email" name="email" class="form-control"
                                    value="{{ $user->email ?? '' }}" readonly>
                            </div>
                        </div>
                    @else
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="name" style="font-size: 1.1em;">First Name <span
                                        class="required-star">*</span></label>
                                <input type="text" name="info[name]" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="middle_name" style="font-size: 1.1em;">Middle Name</label>
                                <input type="text" name="info[middle_name]" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="last_name" style="font-size: 1.1em;">Last Name <span
                                        class="required-star">*</span></label>
                                <input type="text" name="info[last_name]" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="phone" style="font-size: 1.1em;">Phone <span
                                        class="required-star">*</span></label>
                                <input type="text" name="info[phone]" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="email" style="font-size: 1.1em;">Email <span
                                        class="required-star">*</span></label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="company" style="font-size: 1.1em;">Company</label>
                                <select name="info[company_id]" class="form-control">
                                    <option value="" selected>Select a company</option>
                                    <option value="null">None</option>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}">{{ $company->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif

                    {{-- /info --}}
                    {{-- survey --}}
                    <hr style="margin:25px;">
                    @foreach ($form->questions as $question)
                        <div class="form-group">
                            <label for="question_{{ $question->id }}" style="font-size: 1.2em; color: #D69900;">
                                <strong>Question {{ $loop->iteration }}</strong>

                            </label><br>
                            <strong style="color: #012951">{{ $question->question }}</strong>
                            @if ($question->is_required)
                                <span class="required-star">*</span>
                            @endif
                            <br>
                            <input type="hidden" name="answers[{{ $question->id }}][question_id]"
                                value="{{ $question->id }}">

                            @switch($question->type)
                                @case(\App\Enums\QuestionEnum::one_choice)
                                    <div style="display: flex; align-items: center; flex-wrap: wrap; width: 100%;">
                                        @foreach (json_decode($question->options, true) as $option)
                                            <div class="form-check" style="flex-basis: 24%;">
                                                <input class="form-check-input" style="margin-bottom: 15px;" type="radio"
                                                    name="answers[{{ $question->id }}][answer]"
                                                    value="{{ $option['title'] }}">
                                                <label class="form-check-label">{{ $option['title'] }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                @break

                                @case(\App\Enums\QuestionEnum::multi_choice)
                                    <div style="display: flex; align-items: center; flex-wrap: wrap; width: 100%;">
                                        @foreach (json_decode($question->options, true) as $option)
                                            <div class="form-check" style="flex-basis: 24%;">
                                                <input  class="form-check-input" style="margin-bottom: 15px;" type="checkbox"
                                                    name="answers[{{ $question->id }}][answer][]"
                                                    value="{{ $option['title'] }}">
                                                <label class="form-check-label">{{ $option['title'] }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                @break

                                @case(\App\Enums\QuestionEnum::text)
                                    <textarea style="margin-top:15px;" name="answers[{{ $question->id }}][answer]" class="form-control" placeholder="Your answer"
                                        rows="4"></textarea>
                                @break

                                @case(\App\Enums\QuestionEnum::rating)
                                    <div class="star-rating">
                                        @for ($i = 5; $i >= 1; $i--)
                                            <input type="radio" id="star{{ $i }}_{{ $question->id }}"
                                                name="answers[{{ $question->id }}][answer]" value="{{ $i }}"
                                                onclick="handleStarClick({{ $question->id }}, {{ $i }})">
                                            <label for="star{{ $i }}_{{ $question->id }}"
                                                title="{{ $i }} stars">&#9733;</label>
                                        @endfor
                                    </div>
                                @break

                                @case(\App\Enums\QuestionEnum::missing_word)
                                    @php
                                        $sentence = str_replace(
                                            '__',
                                            '<input type="text" name="answers[' .
                                                $question->id .
                                                '][answer]" class="form-control d-inline-block w-auto" placeholder="Fill in the word">',
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
                <div style="display: flex;justify-content: space-around;">
                    <button type="submit" class="btn"
                        style="margin-bottom:15px; background-color: #012951; font-weight: bold; color: #fff; width: 200px; text-align: center; border-radius: 50px; border: 0; padding: 10px 20px; transition: background-color 0.3s;">
                        Submit
                    </button>
                </div>
            </form>
        </div>

        {{-- /form --}}


        {{-- footer --}}
        @include('layouts.footer')
        {{-- /footer --}}

    </main>
    <!-- JS, Popper.js, and jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.5.1/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.5.1/ScrollTrigger.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.5/dist/popper.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"
        integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous">
    </script>
    <script src="https://unpkg.com/material-components-web@14.0.0/dist/material-components-web.min.js"></script>
    <script src="https://unpkg.com/scrollreveal@4.0.9"></script>
    <script src="{{ asset('/vendor/slick/slick.min.js') }}"></script>
    <script src="{{ asset('/js/script.js') }}"></script>
    <script src="{{ asset('/js/mega-menu.js') }}"></script>
    <script src="{{ asset('/js/baseAnimations.js') }}"></script>

    <script type="text/javascript" src="{{ asset('js/main.js?v=' . time()) }}"></script>

    <script src="https://cdn.amcharts.com/lib/4/core.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/maps.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/geodata/worldLow.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>

    <script src="https://unpkg.com/bootstrap-table@1.18.0/dist/bootstrap-table.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('surveyForm');

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(form);
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch('{{ route('survey.submit') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        const submittedMessage = document.getElementById('submittedMessage');

                        if (data.status === 'OK') {
                            form.style.display = 'none';
                            submittedMessage.style.display = 'block';
                        } else if (data.status === 'ERROR') {
                            alert(data.message);
                            document.getElementById('surveyForm').style.display = 'none';
                            document.getElementById('submittedMessage').innerText =
                                'You have Answered Before';
                            document.getElementById('submittedMessage').style.display = 'block';
                        } else {
                            alert('An error occurred while submitting the survey.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred. Please try again later.');
                    });
            });
        });
    </script>
    <script type="text/javascript">
        $('#subscribeForm').submit(function(event) {
            event.preventDefault();

            grecaptcha.ready(function() {
                grecaptcha.execute("{{ env('GOOGLE_RECAPTCHA_KEY') }}", {
                    action: 'subscribe_newsletter'
                }).then(function(token) {
                    $('#subscribeForm').prepend('<input type="hidden" name="token" value="' +
                        token + '">');
                    $('#subscribeForm').unbind('submit').submit();
                });;
            });
        });
    </script>
    <script>
        let previousStarSelection = {};

        function handleStarClick(questionId, starValue) {
            const currentSelection = previousStarSelection[questionId];

            if (currentSelection === starValue) {
                document.getElementById(`star${starValue}_${questionId}`).checked = false;
                previousStarSelection[questionId] = null;
            } else {
                previousStarSelection[questionId] = starValue;
            }
        }
    </script>
</body>

</html>
