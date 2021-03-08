@extends('layouts.app')

@section('content')

    @auth
        <div class="toast" style="position: absolute; top: 18%; right: 2%; z-index: 100;" data-delay="10000"
             role="alert"
             aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <i id="toast-icon" class="fa fa-check mr-2"></i>
                <strong id="welcomeback" class="mr-auto"></strong>
                <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                    <i class="fa fa-window-close"></i>
                </button>
            </div>
            <a id="toast-link" href="#">
                <div class="toast-body">
                </div>
            </a>
        </div>
    @endauth

    <div>
        <div id="carouselWelcome" class="carousel slide carousel-fade pt-xl-5" data-ride="carousel" data-pause="false">
            <ol class="carousel-indicators">
                <li data-target="#carouselWelcome" data-slide-to="0" class="active"></li>
                <li data-target="#carouselWelcome" data-slide-to="1"></li>
                <li data-target="#carouselWelcome" data-slide-to="2"></li>
                <li data-target="#carouselWelcome" data-slide-to="3"></li>
            </ol>
            <div class="carousel-inner pt-xl-5">
                <div class="carousel-item active">
                    <img src="{{ env('IMG_PATH') }}/images/lodge3.jpg" alt="First slide">
                    @include('includes.carouselcaption')
                </div>
                <div class="carousel-item">
                    <img src="{{ env('IMG_PATH') }}/images/lodge1.jpg" alt="Second slide">
                    @include('includes.carouselcaption')
                </div>
                <div class="carousel-item">
                    <img src="{{ env('IMG_PATH') }}/images/lodge2.jpg" alt="Third slide">
                    @include('includes.carouselcaption')
                </div>
                <div class="carousel-item">
                    <img src="{{ env('IMG_PATH') }}/images/lodge4.jpg" alt="Fourth slide">
                    @include('includes.carouselcaption')
                </div>
            </div>
            <a class="carousel-control-prev" href="#carouselWelcome" role="button" data-slide="prev">
                <i class="fas fa-chevron-left fa-3x"></i> <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselWelcome" role="button" data-slide="next">
                <i class="fas fa-chevron-right fa-3x"></i> <span class="sr-only">Next</span>
            </a>
        </div>
    </div>

    <div class="pt-4 pt-md-6 bg-white">
        <div class="container w-lg-70">

            <div class="row my-5 justify-content-center">
                <div class="col-md-6" style="font-family: 'Barlow', sans-serif;">
                    <p><strong>Dear MUUSA friends,</strong></p>
                    <p>Hello Fellow Campers,</p>
                    <p>After much deliberation and without a guarantee that vaccinations will be widely available before
                        July, the MUUSA Planning Council has elected to again hold camp virtually in 2021. We really
                        wanted to make it happen this year, but we can't risk even one camper getting sick if we can
                        avoid it.</p>
                    <p>This is difficult, knowing how much we all miss our camp community, but we believe it is the
                        safest decision. On a positive note, we just confirmed that Trout Lodge will allow us to roll
                        our 2021 deposit (rolled from 2020) to 2022. So we can look forward to all being together again,
                        in person, in 2022.</p>
                    <p>Our APC is hard at work designing virtual programming for 2021. With more time to plan, and the
                        success of Mini MUUSA 2020, you're in store for an even bigger week of programming! Of note, the
                        first day of our traditional week will be July 4 this year, so we may be altering dates. We will
                        be sending out a virtual brochure and more information early next year.</p>
                    <p>With 2022 on the horizon and our deposit held by Trout Lodge for that year, we hope to hold all
                        your paid-in deposits another year. If that puts you in a difficult position, please reply to
                        this email and we'll work out a refund.</p>


                    </p>


                    <p>With gratitude,<br/><br/>

                        MUUSA Planning Council</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-primary py-4">
        <div class="container text-white d-lg-flex justify-content-center">
            <h3 class="my-lg-1 mr-lg-3 font-weight-normal">
                @switch($year->yearmessage)
                    @case(\App\Enums\Yearmessage::CheckinCountdown)
                    Just {{ $year->did_checkin }} days until check-in!
                    @break

                    @case(\App\Enums\Yearmessage::BrochureCountdown)
                    Only {{ $year->did_brochure }} days until the brochure is released!
                    @break

                    @case(\App\Enums\Yearmessage::Preregistration)
                    Lock in your room from last year by paying your deposit!
                    @break

                    @case(\App\Enums\Yearmessage::Filling)
                    Rooms are filling up quickly. <u><a href="mailto:muusa@muusa.org" class="text-white">Contact
                            us</a></u>
                    to see what is still open.
                    @break;

                    @case(\App\Enums\Yearmessage::Custom)
                    {{ $year->custommessage }}
                    @break
                @endswitch
            </h3>
            <div>
                @can('has-paid')
                    <a href="{{ route('campers.index') }}" class="btn btn-secondary">
                        See Your Information for {{ $year->year }} <i class="fas fa-sign-in"></i>
                    </a>
                @else
                    <button type="button" class="btn btn-secondary" data-toggle="modal"
                            data-target="#modal-register">
                        Register for {{ $year->year }} <i class="fas fa-sign-in"></i>
                    </button>
                @endif
            </div>

        </div>
    </div>
    <div class="card-deck p-3">
        <div class="card px-5">
            <img class="card-img-top" src="/images/programs.jpg"
                 alt="Loriana Stucker, moving in for the week"/>
            <div class="card-body">
                <h4 class="card-title">
                    Programs
                </h4>
                <p class="card-text">Couples and singles, with and without children, can enjoy a variety of
                    workshop and
                    recreational activities while children are in programs with others near their own age,
                    building
                    friendships that will last well beyond the week of camp.</p>
            </div>
            <a href="{{ route('programs') }}" class="btn btn-primary">Program Descriptions</a>
        </div>
        <div class="card px-5">
            <img class="card-img-top" src="/images/workshops.jpg"
                 alt="Jay Warner, during a photography workshop"/>
            <div class="card-body">
                <h4 class="card-title">
                    Workshops
                </h4>
                <p class="card-text">Workshops offer opportunities for learning, personal growth, and fun. They
                    are an
                    excellent way to get to know other campers in a small group setting and to benefit from the
                    wonderful talents, skills, and insights the workshop leaders have to offer.</p>
            </div>
            <a href="{{ route('workshops.display') }}" class="btn btn-primary">
                @if($year->is_live)
                    Workshop List
                @else
                    Last Year's Workshops (Sample)
                @endif
            </a>
        </div>
        <div class="card px-5">
            <img class="card-img-bottom" src="/images/biographies.jpg"
                 alt="Chalice tattoo"/>
            <div class="card-body">
                <h4 class="card-title">
                    Morning Celebrations
                </h4>
                <p class="card-text">Each morning, Dr. Glen Thomas Rideout will lead a multi-generational
                    service on the
                    theme topic. Services include children's stories and choral music from the Awesome Choir,
                    led by Pam
                    Blevins Hinkle and accompanied by Bonnie Ettinger.</p>
            </div>
            <a href="{{ route('themespeaker') }}" class="btn btn-primary">Theme Speaker Biography</a>
        </div>
    </div>


    <div class="section bg-secondary" id="testimonial">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="title text-center mb-4">
                        <i class="pe-7s-chat h1 text-primary mb-3"></i>
                        <h3 class="font-22 mb-3">Quotes from the Community</h3>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4">
                    <div class="testi-box mt-4">
                        <div class="testi-desc p-4">
                            <p class="text-muted mb-0">" I love that I started the week not knowing anyone
                                except my
                                children, but ended the week with lifelong friends. "</p>
                        </div>

                        <div class="p-4">
                            <div>
                                <h4 class="font-16 mb-1">Geeta P.</h4>
                                <p class="mb-2">
                                    <small> - Colorado Springs, CO</small>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="testi-box mt-4">
                        <div class="testi-desc p-4">
                            <p class="text-muted mb-0">" MUUSA is a true community for building meaningful
                                friendships--
                                as well as a low stress family vacation where you are not always reaching for
                                your
                                wallet.
                                "</p>
                        </div>

                        <div class="p-4">
                            <div>
                                <h4 class="font-16 mb-1">Roger E.</h4>
                                <p class="mb-2">
                                    <small> - Atlanta, GA</small>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="testi-box mt-4">
                        <div class="testi-desc p-4">
                            <p class="text-muted mb-0">" MUUSA gives me a space to deepen family bonds and
                                recharge
                                connections with my inner humanity. "</p>
                        </div>

                        <div class="p-4">
                            <div>
                                <h4 class="font-16 mb-1">Greg R.</h4>
                                <p class="mb-2">
                                    <small> - Chicago, IL</small>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

