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

<nav class="navbar navbar-expand-lg sticky-top navbar-light bg-primary">

    <a class="header-brand-text" href="/" title="Home">
        <img src="/images/brand35.png" class="logo"
             alt="Midwest Unitarian Universalist Summer Assembly">
        MUUSA
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="infoDropdown" role="button" data-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false">
                    Camp Information
                </a>
                <div class="dropdown-menu" aria-labelledby="infoDropdown">
                    <a href="{{ url('/housing') }}" class="dropdown-item"><i class="far fa-bath fa-fw"></i> Housing
                        Options</a>
                    <a href="{{ url('/programs') }}" class="dropdown-item">
                        <i class="far fa-sitemap fa-fw"></i> Programs</a>
                    <a href="{{ url('/workshops') }}" class="dropdown-item">
                        <i class="far fa-map fa-fw"></i> Workshop List</a>
                    <a href="{{ url('/themespeaker') }}" class="dropdown-item">
                        <i class="far fa-microphone fa-fw"></i> Theme Speakers</a>
                    <a href="{{ url('/cost') }}" class="dropdown-item">
                        <i class="far fa-calculator fa-fw"></i> Cost Calculator</a>
                    <a href="{{ url('/scholarship') }}" class="dropdown-item">
                        <i class="far fa-universal-access fa-fw"></i> Scholarships</a>
                    <a href="{{ url('/excursions') }}" class="dropdown-item">
                        <i class="far fa-binoculars fa-fw"></i> Excursions</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="accountDropdown" role="button" data-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false">
                    Your Account
                </a>
                <div class="dropdown-menu" aria-labelledby="accountDropdown">
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
                    {{--<a href="{{ url('/directory') }}" class="dropdown-item">--}}
                    {{--<i class="far fa-address-book fa-fw"></i> Online Directory</a>--}}
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
            </li>
        </ul>
        <form class="form-inline my-2 my-lg-0">
            <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form>
    </div>
</nav>

<div id="content" class="p-0">

    @yield('content')

</div>

<script src="{{ mix('js/app.js') }}"></script>

</body>
</html>
