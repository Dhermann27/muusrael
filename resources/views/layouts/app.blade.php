<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>

    <meta name="description"
          content="Midwest Unitarian Universalist Summer Assembly, located outside Potosi, Missouri (Formerly Lake Geneva Summer Assembly in Williams Bay, Wisconsin)">
    <meta name="author" content="Dan Hermann">
    <title>
        @hassection('title')
            MUUSA: @yield('title')
        @else
            Midwest Unitarian Universalist Summer Assembly
        @endif
    </title>

    <link rel="stylesheet" href="{{ mix('css/app.css') }}">

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png?v=bOMnaKo3RO"/>
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png?v=bOMnaKo3RO"/>
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png?v=bOMnaKo3RO"/>
    <link rel="manifest" href="/site.webmanifest?v=bOMnaKo3RO"/>
    <link rel="mask-icon" href="/safari-pinned-tab.svg?v=bOMnaKo3RO" color="#5bbad5"/>
    <link rel="shortcut icon" href="/favicon.ico?v=bOMnaKo3RO"/>
    <meta name="apple-mobile-web-app-title" content="MUUSA"/>
    <meta name="application-name" content="MUUSA"/>
    <meta name="msapplication-TileColor" content="#da532c"/>
    <meta name="theme-color" content="#ffffff"/>

    <script src="//cdnjs.cloudflare.com/ajax/libs/retina.js/1.3.0/retina.min.js"></script>

</head>
<body>

<a id="top" href="#content" class="sr-only">Skip to content</a>

<header class="sticky">
    <div class="tagline pt-5 pt-lg-0 bg-primary">
        <div class="container-fluid">
            <div class="float-left info-link">
                <ul class="list-inline mb-0">
                    <li class="list-inline-item">
                        @auth
                            <a href="{{ url('/logout') }}"
                               onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                Logout
                            </a>

                            <form id="logout-form" action="{{ url('/logout') }}" method="POST"
                                  style="display: none;">
                                @crsf
                            </form>
                        @else
                            <a href="{{ url('/login') }}" class="pr-4">Login</a>
                            <a href="{{ url('/register') }}">Create Account</a>
                        @endif
                    </li>
                    @if($year->next_muse !== false)
                        <li class="list-inline-item">
                            <a href="{{ url('/themuse') }}">{{ $year->next_muse }}</a>
                        </li>
                    @else
                        <li class="list-inline-item">
                            @auth
                                {{ Auth::user()->email }}
                            @else
                                <a href="mailto:muusa@muusa.org">
                                    <i class="fas fa-mailbox"></i> muusa@muusa.org
                                </a>
                            @endif
                        </li>
                    @endif
                </ul>
            </div>
            <div class="float-right">
                <ul class="list-inline social-links mb-0">
                    <li class="list-inline-item">
                        <a href="https://www.facebook.com/{{ Auth::guest() ? 'Muusa2013/' : 'groups/Muusans/'}}">
                            <i class="fab fa-facebook-square icon-1x"></i> <span class="sr-only">Facebook</span>
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <a href="https://twitter.com/muusa1" class="nav-link">
                            <i class="fab fa-twitter-square icon-1x"></i> <span class="sr-only">Twitter</span>
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <a href="https://www.youtube.com/watch?v=QNWMdbrjxuE">
                            <i class="fab fa-youtube-square icon-1x"></i> <span class="sr-only">YouTube</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>

    <!--Navbar Start-->
    <nav class="navbar navbar-expand-lg fixed-top navbar-custom" id="navbar">
        <div class="container-fluid">
            <a class="logo text-uppercase" href="/" title="Home">
                <img src="/images/brand35.png" class="logo-light pr-3"
                     alt="Midwest Unitarian Universalist Summer Assembly">
                <img src="/images/brand35.png" class="logo-dark pr-3"
                     alt="Midwest Unitarian Universalist Summer Assembly">
                MUUSA
            </a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse"
                    aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <i class="far fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav ml-auto navbar-center" id="mySidenav">
                    <li class="nav-item">
                        <div class="dropdown">
                            <a class="nav-link dropdown-toggle" href="" role="button" data-toggle="dropdown"
                               aria-haspopup="true" aria-expanded="false">
                                Camp Information
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="{{ url('/housing') }}" class="dropdown-item"><i class="far fa-bath fa-fw"></i>
                                    Housing
                                    Options</a>
                                <a href="{{ url('/programs') }}" class="dropdown-item">
                                    <i class="far fa-sitemap fa-fw"></i> Programs
                                </a>
                                <a href="{{ url('/workshops') }}" class="dropdown-item">
                                    <i class="far fa-map fa-fw"></i> Workshop List
                                </a>
                                <a href="{{ url('/themespeaker') }}" class="dropdown-item">
                                    <i class="far fa-microphone fa-fw"></i> Theme Speakers
                                </a>
                                <a href="{{ url('/cost') }}" class="dropdown-item">
                                    <i class="far fa-calculator fa-fw"></i> Cost Calculator
                                </a>
                                <a href="{{ url('/scholarship') }}" class="dropdown-item">
                                    <i class="far fa-universal-access fa-fw"></i> Scholarships
                                </a>
                                <a href="{{ url('/excursions') }}" class="dropdown-item">
                                    <i class="far fa-binoculars fa-fw"></i> Excursions
                                </a>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="dropdown">
                            <a class="nav-link dropdown-toggle" href="" role="button" data-toggle="dropdown"
                               aria-haspopup="true" aria-expanded="false">
                                Details
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                @if($year->is_live)
                                    <a href="{{ url('/brochure') }}" class="dropdown-item">
                                        <i class="far fa-desktop fa-fw"></i> Web Brochure</a>
                                @endif
                                @if($year->is_calendar)
                                    <a href="{{ url('/calendar') }}" class="dropdown-item">
                                        <i class="far fa-calendar-alt fa-fw"></i> Daily Schedule</a>
                                @endif
                                @if($year->next_muse !== false)
                                    <a href="{{ url('/themuse') }}" class="dropdown-item">
                                        <i class="fal fa-newspaper fa-fw"></i> {{ $year->next_muse }}</a>
                                @endif
                                <a href="{{ url('/directory') }}" class="dropdown-item">
                                    <i class="far fa-address-book fa-fw"></i> Online Directory</a>
                                @if($year->is_artfair)
                                    <a href="{{ url('/artfair') }}" class="dropdown-item">
                                        <i class="far fa-shopping-bag fa-fw"></i> Art Fair Submission</a>
                                @endif
                                <a href="{{ url('/volunteer') }}" class="dropdown-item">
                                    <i class="far fa-handshake fa-fw"></i> Volunteer Opportunities</a>
                                @if($year->is_workshop_proposal)
                                    <a href="{{ url('/proposal') }}" class="dropdown-item">
                                        <i class="fal fa-chalkboard-teacher fa-fw"></i> Workshop Proposal
                                    </a>
                                @endif
                            </div>
                        </div>
                    </li>

                    <li class="nav-item">

                        <div class="dropdown">
                            <a class="nav-link dropdown-toggle" href="" role="button" data-toggle="dropdown"
                               aria-haspopup="true" aria-expanded="false">
                                Registration
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="{{ url('/household') }}" class="dropdown-item">
                                    <i class="far fa-home fa-fw"></i> Household</a>
                                <a href="{{ url('/camper') }}" class="dropdown-item">
                                    <i class="far fa-users fa-fw"></i> Campers</a>
                                <a href="{{ url('/payment') }}" class="dropdown-item">
                                    <i class="far fa-usd-square fa-fw"></i> Payment</a>
                                @if(!$year->is_live)
                                    <div class="dropdown-divider"></div>
                                    <h6 class="dropdown-header">
                                        Opens {{ $year->brochure_date->toFormattedDateString() }}
                                    </h6>
                                    <a href="#" class="dropdown-item disabled">Workshop List</a>
                                    <a href="#" class="dropdown-item disabled">Room Selection</a>
                                    <a href="#" class="dropdown-item disabled">Nametags</a>
                                    <a href="#" class="dropdown-item disabled">Confirmation</a>
                                @else
                                    <a href="{{ url('/workshopchoice') }}" class="dropdown-item">
                                        <i class="far fa-rocket fa-fw"></i>Workshops</a>
                                    <a href="{{ url('/roomselection') }}" class="dropdown-item">
                                        <i class="far fa-bed fa-fw"></i> Room Selection</a>
                                    <a href="{{ url('/nametag') }}" class="dropdown-item">
                                        <i class="far fa-id-card fa-fw"></i> Nametags</a>
                                    <a href="{{ url('/confirm') }}" class="dropdown-item">
                                        <i class="far fa-envelope fa-fw"></i> Confirmation</a>
                                @endif
                            </div>
                        </div>
                    </li>

                    <li class="nav-item"><a href="{{ url('/contact') }}" class="nav-link">Contact Us</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Navbar End -->
</header>


@hassection('title')
    <div class="bg-home bg-primary" id="home">
        @if(isset($background))
            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <div class="home-img">
                        <img src="{{ env('IMG_PATH') }}/images/{{ $background }}" alt="MUUSA"
                             class="img-fluid mx-auto d-block">
                    </div>
                </div>
            </div>
        @endif
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="home-content text-center mb-4">
                        <h1 class="home-title mb-4 text-white">
                            @yield('title')
                        </h1>
                        @hassection('heading')
                            <p>
                                @yield('heading')
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<section id="content" class="p-0">

    @yield('content')

</section>

<!-- footer start -->
<footer class="bg-dark footer text-white">
    <div class="container-fluid">
        <div class="row pt-5">
            <div class="col-lg-6">
                <div class="mb-3">
                    <h6>Located at YMCA of the Ozarks, outside Potosi, Missouri</h6>
                </div>
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2930.017719932353!2d-90.93029498484057!3d37.946753879728526!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x87d99fbc4175e629%3A0xe1c9be8ab89a4075!2sTrout+Lodge%2C+Potosi%2C+MO+63664!5e1!3m2!1sen!2sus!4v1546112609663"
                        width="420" height="320" frameborder="0" style="border:0" allowfullscreen></iframe>
            </div>
            <div class="col-lg-2">
                <h5 class="footer-title text-white mb-3">Camp Information</h5>
                <ul class="list-unstyled footer-list">
                    <li><a href="{{ url('/housing') }}">Housing Options</a></li>
                    <li><a href="{{ url('/programs') }}">Programs</a></li>
                    <li><a href="{{ url('/workshops') }}">Workshop List</a></li>
                    <li><a href="{{ url('/themespeaker') }}">Theme Speaker</a></li>
                    <li><a href="{{ url('/cost') }}">Cost Calculator</a></li>
                    <li><a href="{{ url('/scholarship') }}">Scholarships</a></li>
                    <li><a href="{{ url('/excursions') }}">Excursions</a></li>
                </ul>
            </div>
            <div class="col-lg-2">
                <h5 class="footer-title text-white mb-3">Details</h5>
                <ul class="list-unstyled footer-list">
                    @if($year->is_live)
                        <li><a href="{{ url('/brochure') }}">Web Brochure</a></li>
                    @endif
                    @if($year->is_calendar)
                        <li><a href="{{ url('/calendar') }}">Daily Schedule</a></li>
                    @endif
                    @if($year->next_muse !== false)
                        <li><a href="{{ url('/themuse') }}">{{ $year->next_muse }}</a></li>
                    @endif
                    <li><a href="{{ url('/directory') }}">Online Directory</a></li>
                    @if($year->is_artfair)
                        <li><a href="{{ url('/artfair') }}">Art Fair Submission</a></li>
                    @endif
                    <li><a href="{{ url('/volunteer') }}">Volunteer Opportunities</a></li>
                    @if($year->is_workshop_proposal)
                        <li><a href="{{ url('/proposal') }}">Workshop Proposal</a></li>
                    @endif
                </ul>
            </div>
            <div class="col-lg-2">
                <h5 class="footer-title text-white mb-3">Registration</h5>
                <ul class="list-unstyled footer-list">
                    <li><a href="{{ url('/household') }}">Household</a></li>
                    <li><a href="{{ url('/camper') }}">Campers</a></li>
                    <li><a href="{{ url('/payment') }}">Payment</a></li>
                    @if(!$year->is_live)
                        <hr/>
                        <h6 class="dropdown-header">
                            Opens {{ $year->brochure_date->toFormattedDateString() }}
                        </h6>
                        <li>Workshop List</li>
                        <li>Room Selection</li>
                        <li>Nametags</li>
                        <li>Confirmation</li>
                    @else
                        <li><a href="{{ url('/workshopchoice') }}">Workshops</a></li>
                        <li><a href="{{ url('/roomselection') }}">Room Selection</a></li>
                        <li><a href="{{ url('/nametag') }}">Nametags</a></li>
                        <li><a href="{{ url('/confirm') }}">Confirmation</a></li>
                    @endif
                </ul>
            </div>
        </div>
        <!-- end row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="py-4">
                    <div class="text-center">
                        <p class="text-white-50">{{ $year->year }} &copy; Midwest Unitarian Universalist Summer
                            Assembly. Design by <a href="https://coderthemes.com/" target="_blank"
                                                   class="text-white">Coderthemes</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->
    </div>
    <!-- container-fluid end -->
</footer>
<!-- footer end -->

<script src="{{ mix('js/app.js') }}"></script>

@yield('script')

</body>
</html>
