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
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                Logout
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                  style="display: none;">
                                @csrf
                            </form>
                        @else
                            <a href="{{ route('login') }}">Login</a>
                        @endif
                    </li>
                    @if($year->next_muse !== false)
                        <li class="list-inline-item">
                            <a href="{{ route('themuse') }}">{{ $year->next_muse }}</a>
                        </li>
                    @else
                        <li class="list-inline-item">
                            @auth
                                <i class="fa fa-user"></i> {{ Auth::user()->email }}
                            @else
                                <a href="mailto:muusa@muusa.org">
                                    Questions? <i class="fas fa-mailbox"></i> muusa@muusa.org
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
                     alt="Midwest Unitarian Universalist Summer Assembly" data-no-retina>
                <img src="/images/brand35.png" class="logo-dark pr-3"
                     alt="Midwest Unitarian Universalist Summer Assembly" data-no-retina>
                MUUSA
            </a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse"
                    aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <i class="far fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav ml-auto navbar-center" id="mySidenav">

                    <li class="nav-item mt-1"><a href="{{ route('contact') }}" class="nav-link">Contact Us</a></li>

                    <li class="nav-item mt-1">
                        <div class="dropdown">
                            <a class="nav-link dropdown-toggle" href="" role="button" data-toggle="dropdown"
                               aria-haspopup="true" aria-expanded="false">
                                Camp Information
                            </a>
                            <div class="dropdown-menu dropdown-menu-right mt-0">
                                <a href="{{ route('housing') }}" class="dropdown-item"><i class="far fa-bath fa-fw"></i>
                                    Housing
                                    Options</a>
                                <a href="{{ route('programs') }}" class="dropdown-item">
                                    <i class="far fa-sitemap fa-fw"></i> Programs
                                </a>
                                <a href="{{ route('workshops.display') }}" class="dropdown-item">
                                    <i class="far fa-map fa-fw"></i> Workshop List
                                </a>
                                <a href="{{ route('themespeaker') }}" class="dropdown-item">
                                    <i class="far fa-microphone fa-fw"></i> Theme Speakers
                                </a>
                                <a href="{{ route('cost') }}" class="dropdown-item">
                                    <i class="far fa-calculator fa-fw"></i> Cost Calculator
                                </a>
                                <a href="{{ route('scholarship') }}" class="dropdown-item">
                                    <i class="far fa-universal-access fa-fw"></i> Scholarships
                                </a>
                                <a href="{{ route('workshops.excursions') }}" class="dropdown-item">
                                    <i class="far fa-binoculars fa-fw"></i> Excursions
                                </a>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item mt-1">
                        <div class="dropdown">
                            <a class="nav-link dropdown-toggle" href="" role="button" data-toggle="dropdown"
                               aria-haspopup="true" aria-expanded="false">
                                Details
                            </a>
                            <div class="dropdown-menu dropdown-menu-right mt-0">
                                @if($year->is_live)
                                    <a href="{{ route('brochure') }}" class="dropdown-item">
                                        <i class="far fa-desktop fa-fw"></i> Web Brochure</a>
                                @endif
                                @if($year->is_calendar)
                                    <a href="#" class="dropdown-item">
                                        <i class="far fa-calendar-alt fa-fw"></i> Daily Schedule</a>
                                @endif
                                @if($year->next_muse !== false)
                                    <a href="#" class="dropdown-item">
                                        <i class="fal fa-newspaper fa-fw"></i> {{ $year->next_muse }}</a>
                                @endif
                                <a href="#" class="dropdown-item">
                                    <i class="far fa-address-book fa-fw"></i> Online Directory</a>
                                @if($year->is_artfair)
                                    <a href="#" class="dropdown-item">
                                        <i class="far fa-shopping-bag fa-fw"></i> Art Fair Submission</a>
                                @endif
                                <a href="#" class="dropdown-item">
                                    <i class="far fa-handshake fa-fw"></i> Volunteer Opportunities</a>
                                @if($year->is_workshop_proposal)
                                    <a href="#" class="dropdown-item">
                                        <i class="fal fa-chalkboard-teacher fa-fw"></i> Workshop Proposal
                                    </a>
                                @endif
                            </div>
                        </div>
                    </li>

                    <li class="nav-item mt-1">
                        @auth
                            <div class="dropdown">
                                <a class="nav-link dropdown-toggle" href="" role="button" data-toggle="dropdown"
                                   aria-haspopup="true" aria-expanded="false">
                                    Registration
                                </a>
                                <div class="dropdown-menu dropdown-menu-right mt-0">
                                    {{--                                    <a href="{{ route('household') }}" class="dropdown-item">--}}
                                    {{--                                        <i class="far fa-home fa-fw"></i> Household</a>--}}
                                    {{--                                    <a href="{{ route('camper') }}" class="dropdown-item">--}}
                                    {{--                                        <i class="far fa-users fa-fw"></i> Campers</a>--}}
                                    {{--                                    <a href="{{ route('payment') }}" class="dropdown-item">--}}
                                    {{--                                        <i class="far fa-usd-square fa-fw"></i> Payment</a>--}}
                                    {{--                                    @if(!$year->is_live)--}}
                                    {{--                                        <div class="dropdown-divider"></div>--}}
                                    {{--                                        <h6 class="dropdown-header">--}}
                                    {{--                                            Opens {{ $year->brochure_date }}--}}
                                    {{--                                        </h6>--}}
                                    {{--                                        <a href="#" class="dropdown-item disabled">Workshop List</a>--}}
                                    {{--                                        <a href="#" class="dropdown-item disabled">Room Selection</a>--}}
                                    {{--                                        <a href="#" class="dropdown-item disabled">Nametags</a>--}}
                                    {{--                                        <a href="#" class="dropdown-item disabled">Confirmation</a>--}}
                                    {{--                                    @else--}}
                                    {{--                                        <a href="{{ route('workshopchoice') }}" class="dropdown-item">--}}
                                    {{--                                            <i class="far fa-rocket fa-fw"></i>Workshops</a>--}}
                                    {{--                                        <a href="{{ route('roomselection') }}" class="dropdown-item">--}}
                                    {{--                                            <i class="far fa-bed fa-fw"></i> Room Selection</a>--}}
                                    {{--                                        <a href="{{ route('nametag') }}" class="dropdown-item">--}}
                                    {{--                                            <i class="far fa-id-card fa-fw"></i> Nametags</a>--}}
                                    {{--                                        <a href="{{ route('confirm') }}" class="dropdown-item">--}}
                                    {{--                                            <i class="far fa-envelope fa-fw"></i> Confirmation</a>--}}
                                    {{--                                    @endif--}}
                                </div>
                            </div>
                        @else
                            <button type="button" class="btn btn-info btn-sm my-3" data-toggle="modal"
                                    data-target="#modal-register" dusk="register_now">
                                Register Now <i class="fas fa-sign-in"></i>
                            </button>
                        @endif
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Navbar End -->
</header>


@hassection('title')
    @hasSection('image')
        <div class="jumbotron jumbotron-fluid text-white"
             style="background-size: cover; background-position-y: bottom; background-image: @yield('image');">
            @else
                <div class="jumbotron jumbotron-fluid text-white bg-primary">
                    @endif
                    <div class="container mt-5 pt-5">
                        <h1 class="display-4">
                            @yield('title')
                        </h1>
                        @hassection('heading')
                            <p class="lead">
                                @yield('heading')
                            </p>
                        @endif
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
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2930.017719932353!2d-90.93029498484057!3d37.946753879728526!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x87d99fbc4175e629%3A0xe1c9be8ab89a4075!2sTrout+Lodge%2C+Potosi%2C+MO+63664!5e1!3m2!1sen!2sus!4v1546112609663"
                                width="420" height="320" frameborder="0" style="border:0" allowfullscreen></iframe>
                        </div>
                        <div class="col-lg-2">
                            <h5 class="footer-title text-white mb-3">Camp Information</h5>
                            <ul class="list-unstyled footer-list">
                                <li><a href="{{ route('housing') }}">Housing Options</a></li>
                                <li><a href="{{ route('programs') }}">Programs</a></li>
                                <li><a href="{{ route('workshops.display') }}">Workshop List</a></li>
                                <li><a href="{{ route('themespeaker') }}">Theme Speaker</a></li>
                                <li><a href="{{ route('cost') }}">Cost Calculator</a></li>
                                <li><a href="{{ route('scholarship') }}">Scholarships</a></li>
                                <li><a href="{{ route('workshops.excursions') }}">Excursions</a></li>
                            </ul>
                        </div>
                        <div class="col-lg-2">
                            <h5 class="footer-title text-white mb-3">Details</h5>
                            <ul class="list-unstyled footer-list">
                                @if($year->is_live)
                                    <li><a href="{{ route('brochure') }}">Web Brochure</a></li>
                                @endif
                                @if($year->is_calendar)
                                    <li><a href="#">Daily Schedule</a></li>
                                @endif
                                @if($year->next_muse !== false)
                                    <li><a href="#">{{ $year->next_muse }}</a></li>
                                @endif
                                <li><a href="#">Online Directory</a></li>
                                @if($year->is_artfair)
                                    <li><a href="#">Art Fair Submission</a></li>
                                @endif
                                <li><a href="#">Volunteer Opportunities</a></li>
                                @if($year->is_workshop_proposal)
                                    <li><a href="#">Workshop Proposal</a></li>
                                @endif
                            </ul>
                        </div>
                        <div class="col-lg-2">
                            @auth
                                <h5 class="footer-title text-white mb-3">Registration</h5>
                                <ul class="list-unstyled footer-list">
                                    {{--                                    <li><a href="{{ route('household') }}">Household</a></li>--}}
                                    {{--                                    <li><a href="{{ route('camper') }}">Campers</a></li>--}}
                                    {{--                                    <li><a href="{{ route('payment') }}">Payment</a></li>--}}
                                    {{--                                    @if(!$year->is_live)--}}
                                    {{--                                        <hr/>--}}
                                    {{--                                        <h6 class="dropdown-header">--}}
                                    {{--                                            Opens {{ $year->brochure_date }}--}}
                                    {{--                                        </h6>--}}
                                    {{--                                        <li>Workshop List</li>--}}
                                    {{--                                        <li>Room Selection</li>--}}
                                    {{--                                        <li>Nametags</li>--}}
                                    {{--                                        <li>Confirmation</li>--}}
                                    {{--                                    @else--}}
                                    {{--                                        <li><a href="{{ route('workshopchoice') }}">Workshops</a></li>--}}
                                    {{--                                        <li><a href="{{ route('roomselection') }}">Room Selection</a></li>--}}
                                    {{--                                        <li><a href="{{ route('nametag') }}">Nametags</a></li>--}}
                                    {{--                                        <li><a href="{{ route('confirm') }}">Confirmation</a></li>--}}
                                    {{--                                    @endif--}}
                                </ul>
                            @else
                                <button type="button" class="btn btn-info font-weight-bold" data-toggle="modal"
                                        data-target="#modal-register">
                                    Register Now <i class="fas fa-sign-in"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                    <!-- end row -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="py-4">
                                <div class="text-center">
                                    <p class="text-white-50">{{ $year->year }} &copy; Midwest Unitarian Universalist
                                        Summer Assembly. Design by <a href="https://coderthemes.com/" target="_blank"
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

            <!-- Modal -->
            <div class="modal fade" id="modal-register" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Get Registered for {{ $year->year }}!</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="container-fluid mx-0">
                                <div class="row">
                                    <div class="col-md-6 pr-md-5">
                                        <h5>Returning Campers</h5>

                                        <form id="login" method="post" action="{{ url('/login') }}">
                                            @csrf

                                            <div class="form-group row">
                                                <label for="email_login" class="form-label">Email</label>
                                                <input id="email_login" type="text" class="form-control"
                                                       name="email" required>
                                            </div>

                                            <div class="form-group row">
                                                <label for="password_login" class="form-label">Password</label>
                                                <input id="password_login" type="password" class="form-control"
                                                       name="password" required>
                                            </div>

                                            <div class="form-group row">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="remember"
                                                           id="remember">
                                                    <label class="form-check-label" for="remember">
                                                        Remember me on this computer?
                                                    </label>
                                                </div>
                                            </div>

                                            @if (Route::has('password.request'))
                                                <div class="form-group row float-sm-right">

                                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                                        {{ __('Forgot Your Password?') }}
                                                    </a>
                                                </div>
                                            @endif

                                            <a class="btn d-none" data-toggle="collapse" href="#login-searching"
                                               role="button" aria-expanded="false">
                                                #
                                            </a>
                                            <a class="btn d-none" data-toggle="collapse" href="#login-found"
                                               role="button"
                                               aria-expanded="false">
                                                #
                                            </a>

                                            <div id="login-searching" class="alert alert-info w-100 collapse">
                                                <h6>Welcome back!</h6>
                                                <p>Retrieving your records... <i
                                                        class="fad fa-spinner-third fa-spin"></i>
                                                </p>
                                            </div>

                                            <div id="login-found" class="form-group row w-100 collapse">
                                                <label for="password_login" class="form-label">Which campers will be
                                                    attending?</label>
                                                <select id="login-campers" name="login-campers" class="custom-select"
                                                        multiple data-toggle="tooltip" data-placement="top"
                                                        title="Hold down CTRL or Command to select multiple campers.">
                                                </select>
                                                <button id="selectallcampers" class="btn btn-secondary btn-sm mt-1">
                                                    <i class="fas fa-users"></i> Select All
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-md-6 pl-md-5">
                                        <h5>New Campers</h5>

                                        <form id="create" method="post" action="{{ url('/register') }}">
                                            @csrf

                                            <div class="form-group row">
                                                <label for="email_create" class="form-label">Email</label>
                                                <input id="email_create" type="text" class="form-control"
                                                       name="email" required>
                                            </div>

                                            <div class="form-group row">
                                                <label for="password_create" class="form-label">Password</label>
                                                <input id="password_create" type="password" class="form-control"
                                                       name="password" required>
                                            </div>

                                            <div class="form-group row">
                                                <label for="confirm_create" class="form-label">Confirm Password</label>
                                                <input id="confirm_create" type="password" class="form-control"
                                                       name="password_confirmation" required>
                                            </div>

                                            <div class="form-group row">
                                                <div class="number-spinner">
                                                    <label for="newcampers" class="form-label">How many campers will be
                                                        attending?</label>
                                                    <div class="input-group float-sm-right">
                                                        <div class="input-group-prepend">
                                                            <button type="button" class="btn btn-default spinner"
                                                                    data-dir="up"><i class="far fa-plus"></i></button>
                                                        </div>
                                                        <input id="newcampers" class="form-control" name="newcampers"
                                                               value="1"/>
                                                        <div class="input-group-append">
                                                            <button type="button" class="btn btn-default spinner"
                                                                    data-dir="dwn"><i class="far fa-minus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button id="begin_reg" type="button" class="btn btn-primary">Begin Registration</button>
                        </div>
                    </div>
                </div>
            </div>
            <script src="{{ mix('js/app.js') }}"></script>

        @yield('script')

</body>
</html>
