<nav>
    <div class="upper-navbar clearfix">
        <div class="container">
            <div class="nav-item-right links">
                {{-- @if (isset($countryPage) && !is_null($countryPage))
                    <a href="">{{ $countryPage->country->name ?? 'General' }}</a>
                @endif --}}
                <a href="{{ $website_url }}">Home</a>
                <a href="{{ $website_url }}/about">About Us</a>
                <a href="{{ $website_url }}/meet-our-instructors">Meet Your Instructors</a>
                <a href="{{ $website_url }}/events">Events</a>
                <div class="mega-dropdown" style="display: inline-block;">
                    <a class="nav-link dropdown-toggle" style="padding-right: 0" id="navbarDropdown"
                        role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Careers
                    </a>
                    <div class="mega-menu mega-one-col" aria-labelledby="navbarDropdown">
                        <a class="mega-item" href="{{ $website_url }}/become-an-instructor">Become an
                            instructor</a>
                        <a class="mega-item" href="{{ $website_url }}/positions">Vacancies</a>
                    </div>
                </div>
                <div class="mega-dropdown" style="display: inline-block;">
                    <a class="nav-link dropdown-toggle" style="padding-right: 0" id="navbarDropdown"
                        role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Media Centre
                    </a>
                    <div class="mega-menu mega-one-col" aria-labelledby="navbarDropdown">

                        <a class="mega-item" href="{{ $website_url }}/articles">Articles</a>
                        <a class="mega-item" href="{{ $website_url }}/news">News</a>
                        <a class="mega-item" href="{{ $website_url }}/gallery">Photos</a>
                        <a class="mega-item" href="{{ $website_url }}/meet-our-instructors">Videos</a>
                    </div>
                </div>
                <a href="{{ $website_url }}/contact">Contact</a>
            </div>

        </div>
    </div>
    <div class="navbar navbar-expand-lg navbar-dark" role="navigation">
        <div class="container">
            <a id="logo" class="navbar-brand" href="{{ $website_url }}">
                <img src="{{ asset('whiteLogo.png') }}" width="150" alt="LPC Logo" loading="lazy">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse"
                data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item mega-dropdown">
                        <a class="nav-link dropdown-toggle" role="button">
                            Classroom Courses
                        </a>
                        <div class="mega-menu" aria-labelledby="navbarDropdown">
                            @foreach ($categories as $item)
                                @if ($item->type != 'OtherCourses')
                                    <a class="mega-item" href="{{ $website_url }}/{{ $item->link_id }}"><i
                                            class="fas fa-arrow-circle-right"
                                            style="color: #DE8C35; margin-right: 0.3125rem"></i>{{ $item->type }}</a>
                                @endif
                            @endforeach
                        </div>
                    </li>
                    <li class="nav-item mega-dropdown">
                        <a class="nav-link dropdown-toggle" role="button">
                            Online Courses
                        </a>
                        <div class="mega-menu" aria-labelledby="navbarDropdown">
                            <a class="mega-item" href="{{ $website_url }}/online/categories"><i
                                    class="fas fa-arrow-circle-right"
                                    style="color: #DE8C35; margin-right: 0.3125rem"></i>Live online
                                learning</a>
                            <a class="mega-item" href="{{ $website_url }}/online/self-learning"><i
                                    class="fas fa-arrow-circle-right"
                                    style="color: #DE8C35; margin-right: 0.3125rem"></i>Self-learning</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ $website_url }}/diplomas" id="navbarDropdown"
                            role="button">Diplomas</a>
                    </li>

                    <li class="nav-item mega-dropdown">
                        <a class="nav-link dropdown-toggle" role="button">
                            Training Venues
                        </a>
                        <div class="mega-menu" aria-labelledby="navbarDropdown">
                            @foreach ($cities as $item)
                                <a class="mega-item" href="{{ $website_url }}/{{ $item->link_id }}"><i
                                        class="fas fa-arrow-circle-right"
                                        style="color: #DE8C35; margin-right: 0.3125rem"></i>{{ $item->name }}</a>
                            @endforeach
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ $website_url }}/in-house-training" id="navbarDropdown"
                            role="button">In House Training</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ $website_url }}/customise-your-course"
                            id="navbarDropdown" role="button">Customise Your Course</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ $website_url }}/consulting-services"
                            id="navbarDropdown" role="button">Consulting</a>
                    </li>

                </ul>
            </div>
        </div>
    </div>
</nav>
