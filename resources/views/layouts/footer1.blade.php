@php
$footer_contact1=\App\Models\FooterSetting::where('name','footer_contact1')->first();
$contact_message1=$footer_contact1?$footer_contact1->value:"";
$footer_contact2=\App\Models\FooterSetting::where('name','footer_contact2')->first();
$contact_message2=$footer_contact2?$footer_contact2->value:"";
$footer_contact3=\App\Models\FooterSetting::where('name','footer_contact3')->first();
$contact_message3=$footer_contact3?$footer_contact3->value:"";

$footer_contact4=\App\Models\FooterSetting::where('name','footer_contact4')->first();
$contact_message4=$footer_contact4?$footer_contact4->value:"";

$footer_categories=\App\Models\FooterSetting::where('name','footer_categories')->first();
$footer_cities=\App\Models\FooterSetting::where('name','footer_cities')->first();

$footer_categories_arr=explode(',',$footer_categories?$footer_categories->value:"");
$footer_cities_arr=explode(',',$footer_cities?$footer_cities->value:"");

$_footer_categories=\Modules\Lms\Models\Category::whereIn('id',$footer_categories_arr)->get();
$_footer_cities=\Modules\Lms\Models\City::whereIn('id',$footer_cities_arr)->get();

$footer_whatsapp=\App\Models\FooterSetting::where('name','footer_whatsapp')->first();
$_footer_whatsapp=$footer_whatsapp?$footer_whatsapp->value:"";

$footer_facebook=\App\Models\FooterSetting::where('name','footer_facebook')->first();
$_footer_facebook=$footer_facebook?$footer_facebook->value:"";

$footer_twitter=\App\Models\FooterSetting::where('name','footer_twitter')->first();
$_footer_twitter=$footer_twitter?$footer_twitter->value:"";

$footer_linkedin=\App\Models\FooterSetting::where('name','footer_linkedin')->first();
$_footer_linkedin=$footer_linkedin?$footer_linkedin->value:"";
@endphp

<!-- Bootstrap CSS -->
{{-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
    integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous"> --}}
{{-- icons --}}
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">

<link rel="stylesheet" href="{{ asset('/css/footer.css') }}">
<link rel="stylesheet" href="{{ asset('/css/footer1.css') }}">

<link rel="stylesheet" href="{{ asset('css/new-style/profile.css') }}">

<style>
    .footer-list span {
        padding-left: 0;
        padding-right: 0;
        position: relative
    }

    .footer-list {
        position: relative;
        text-decoration: none;
        float: left;
        padding: 0;
        margin: 0
    }

    .footer-list>li {
        position: relative;
        text-decoration: none;
        padding: 0;
        margin: 0;
        list-style: none;
        padding-left: 24px
    }

    .footer-list>li i {
        position: absolute;
        left: 0;
        top: 5px
    }

    .footer-logo {
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
<footer class="text-md-left">
    <div class="container">
        <div class="row m-0  mb-3">
            <div class="col-12 col-md-3 footer-logo">
                <img src="{{ asset('whiteLogo.png') }}" alt="LPC Logo" class="logo" width="170">
            </div>
            <div class="col-md-9">
                <div class="row mb-4">

                    <div class="col-12 col-md-6">
                        <div class="label">LONDON</div>
                        <div class="content pr-4">
                            {!! $contact_message1 !!}
                            {{-- <p>Oxford Street, 25 N Row, London W1K 6DJ</p>
                            <p>+44 20 36 916 970 | +44 20 80 900 464</p>
                            <p>info@lpcentre.com</p> --}}
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="label"> DUBAI</div>
                        <div class="content pr-4">
                            {{-- <p>Business Bay, ParkLane Tower, Offices 718 - 719</p>
                            <p>+971 43 88 00 94</p>
                            <p>dubai.training@lpcentre.com</p> --}}
                            {!! $contact_message2 !!}
                        </div>
                    </div>


                </div>
                <div class="row mb-2">
                    <div class="col-12 col-md-6">
                        <div class="label text-uppercase">Kuala Lumpur</div>
                        <div class="content pr-4">
                            {{-- <p>No. 3273 Level 32, Menara Prestige, 1, Jalan Pinang, 50450 Kuala Lumpur</p>
                            <p>+60 19 305 5694</p> --}}
                            {!! $contact_message3 !!}
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="label text-uppercase">Barcelona</div>
                        <div class="content pr-4">
                            {{-- <p>Passeig de Gràcia, 21, planta principal, 08007 Barcelona, Spain</p>
                            <p>+34 931 311 600</p> --}}
                            {!! $contact_message4 !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row orange-border">
            <div class="col-12 col-md-6 col-lg-7">
                <div class="label mt-0 text-center-mobile">Get in touch</div>
                <div class="get-in-touch">
                    <div class="footer-icon wp-icon">
                        <a class="h-100 w-100 d-flex justify-content-center align-items-center" href="{{ $_footer_whatsapp }}"
                            target="_blank"><i class="fab fa-whatsapp"></i></a>
                    </div>
                    <div class="footer-icon fb-icon">
                        <a class="h-100 w-100 d-flex justify-content-center align-items-center" href="{{ $_footer_facebook }}"
                            target="_blank"><i class="fab fa-facebook-f"></i></a>
                    </div>
                    <div class="footer-icon twitter-icon">
                        <a class="h-100 w-100 d-flex justify-content-center align-items-center" href="{{ $_footer_twitter }}"
                            target="_blank"><i class="fab fa-twitter"></i></a>
                    </div>
                    <div class="footer-icon linkedin-icon">
                        <a class="h-100 w-100 d-flex justify-content-center align-items-center" href="{{ $_footer_linkedin }}"
                            target="_blank"><i class="fab fa-linkedin"></i></a>
                    </div>
                </div>
            </div>


        </div>
        <div
            class="row align-items-baseline justify-content-center flex-column-reverse flex-md-row bottom-banner">
            <div class="col-12 col-md-auto">
                <div class="copyright" style="font-size:12px;">
                    Copyright &copy; 2024 lpcentre.com All Rights Reserved.
                </div>
            </div>
            <div class="col-12 col-md-auto">
                <div class="links text-center" style="font-size:12px;">
                    <a href="{{ $website_url }}/contact">Contact</a> -
                    <a href="{{ $website_url }}/terms-and-conditions">Terms and Conditions</a> -
                    <a href="{{ $website_url }}/privacy-policy">Privacy Policy</a> -
                    <a href="{{ $website_url }}/quality-policy">Quality Policy</a> -
                    <a href="{{ $website_url }}/become-an-instructor">Become an instructor</a> -
                    <a href="{{ $website_url }}/positions">Vacancies</a> -
                    <a href="{{ $website_url }}/sitemap">Sitemap</a>
                </div>
            </div>
        </div>
    </div>
</footer>

    <script src="https://unpkg.com/bootstrap-table@1.18.0/dist/bootstrap-table.min.js"></script>
