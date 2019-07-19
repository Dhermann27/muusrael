@extends('layouts.app')

@section('content')
    <div>
        <div id="carouselIndicators" class="carousel slide carousel-fade" data-ride="carousel" data-pause="false">
            <ol class="carousel-indicators">
                <li data-target="#carouselIndicators" data-slide-to="0" class="active"></li>
                <li data-target="#carouselIndicators" data-slide-to="1"></li>
                <li data-target="#carouselIndicators" data-slide-to="2"></li>
                <li data-target="#carouselIndicators" data-slide-to="3"></li>
            </ol>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img class="d-block w-100" src="{{ env('IMG_PATH') }}/images/lodge3.jpg" alt="First slide">
                    @include('includes.carouselcaption')
                </div>
                <div class="carousel-item">
                    <img class="d-block w-100" src="{{ env('IMG_PATH') }}/images/lodge1.jpg" alt="Second slide">
                    @include('includes.carouselcaption')
                </div>
                <div class="carousel-item">
                    <img class="d-block w-100" src="{{ env('IMG_PATH') }}/images/lodge2.jpg" alt="Third slide">
                    @include('includes.carouselcaption')
                </div>
                <div class="carousel-item">
                    <img class="d-block w-100" src="{{ env('IMG_PATH') }}/images/lodge5.jpg" alt="Fourth slide">
                    @include('includes.carouselcaption')
                </div>
            </div>
            <a class="carousel-control-prev" href="#carouselIndicators" role="button" data-slide="prev">
                <i class="fas fa-chevron-left fa-3x"></i> <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselIndicators" role="button" data-slide="next">
                <i class="fas fa-chevron-right fa-3x"></i> <span class="sr-only">Next</span>
            </a>
        </div>
    </div>

    <div class="pt-4 pt-md-6 bg-white">
        <div class="container w-100 w-lg-70">
            <div class="row mt-2">
                <div class="col-lg-6 d-sm-flex">
                    <a href="{{ url('/brochure') }}">
                        <img id="brochureimg" class="card-img-top img-fluid" src="/images/brochure.png"
                             alt="Web Brochure cover">
                    </a>
                </div>
                <div class="col-lg-6 d-sm-flex align-content-center d-flex align-items-center">
                    <div class="mr-auto py-0 pl-lg-5 my-3 my-md-0">
                        <h2 class="display-4 mt-3 mt-lg-0">
                            Web Brochure
                        </h2>
                        <p class="line-height-30 py-md-2 op-7">
                            @if($year->is_live)
                                The easiest way to learn all about MUUSA is to read the brochure, put out by our
                                Planning Council. It has it all: workshop descriptions, housing options, frequently
                                asked questions, and more.
                            @else
                                While you can register right now to reserve your spot, our Planning Council is working
                                diligently to prepare this year's brochure, which should be ready on February 1. You can
                                currently see last year's to get an idea of what it might contain.
                            @endif
                        </p>
                        <a href="{{ url('/brochure') }}"
                           class="mb-1 py-2 px-4 btn btn-primary btn-shadow btn-flat btn-sm btn-bold text-uppercase text-letter-spacing rounded-0">
                            <i class="far fa-file-pdf mr-2"></i> Take a look
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-primary py-4">
        <div class="container text-white d-lg-flex justify-content-center">
            <h3 class="my-lg-0 mr-lg-3 font-weight-normal">
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
                    Rooms are filling up quickly. <u><a href="mailto:muusa@muusa.org" class="text-white">Contact us</a></u>
                    to see what is still open.
                    @break;

                    @case(\App\Enums\Yearmessage::Custom)
                    {{ $year->custommessage }}
                    @break
                @endswitch
            </h3>
            <div>
                <a href="{{ url('/registration') }}" class="btn btn-info font-weight-bold">Register Now
                    <i class="far fa-arrow-right"></i></a>
            </div>

        </div>
    </div>
    <div class="card-deck p-3">
        <div class="card">
            <img class="card-img-top img-fluid" src="/images/programs@half.jpg" alt="Laurel and Jim Hermann"/>
            <div class="card-body">
                <h4 class="card-title">
                    Programs
                </h4>
                <p class="card-text">Couples and singles, with and without children, can enjoy a variety of workshop and
                    recreational activities while children are in programs with others near their own age, building
                    friendships that will last well beyond the week of camp.</p>
            </div>
            <a href="{{ url('/programs') }}" class="btn btn-primary">Program Descriptions</a>
        </div>
        <div class="card">
            <img class="card-img-top img-fluid" src="/images/housing@half.jpg" alt="Trout Lodge"/>
            <div class="card-body">
                <h4 class="card-title">
                    Housing
                </h4>
                <p class="card-text">YMCA of the Ozarks, Trout Lodge, is located on 5,200 acres of pine and oak forest
                    on a private 360-acre lake 75 miles southwest of St. Louis, Missouri, outside of Potosi.
                    Accommodations are available for all budgets.</p>
            </div>
            <a href="{{ url('/housing') }}" class="btn btn-primary">Housing Options</a>
        </div>
        <div class="card">
            <img class="card-img-top img-fluid" src="/images/workshops@half.jpg"
                 alt="Justin and Eleanor Hobbs, performing at coffeehouse"/>
            <div class="card-body">
                <h4 class="card-title">
                    Workshops
                </h4>
                <p class="card-text">Workshops offer opportunities for learning, personal growth, and fun. They are an
                    excellent way to get to know other campers in a small group setting and to benefit from the
                    wonderful talents, skills, and insights the workshop leaders have to offer.</p>
            </div>
            <a href="{{ url('/workshops') }}" class="btn btn-primary">
                @if($year->is_live)
                    Workshop List
                @else
                    Last Year's Workshops (Sample)
                @endif
            </a>
        </div>
    </div>

    <div class="card-deck p-3">
        <div class="card">
            <img class="card-img-top img-fluid" src="/images/biographies@half.jpg"
                 alt="Chalice lit during morning celebrations"/>
            <div class="card-body">
                <h4 class="card-title">
                    Morning Celebrations
                </h4>
                <p class="card-text">Each morning, Dr. Glen Thomas Rideout will lead a multi-generational service on the
                    theme topic. Services include children's stories and choral music from the Awesome Choir, led by Pam
                    Blevins Hinkle and accompanied by Bonnie Ettinger.</p>
            </div>
            <a href="{{ url('/themespeaker') }}" class="btn btn-primary">Theme Speaker Biography</a>
        </div>
        <div class="card">
            <img class="card-img-top img-fluid" src="/images/scholarship@half.jpg"
                 alt="A hummingbird in dire need of a sugar scholarship"/>
            <div class="card-body">
                <h4 class="card-title">
                    Scholarship Opportunities
                </h4>
                <p class="card-text">If finances are tight and MUUSA doesn't quite fit into your budget this year, we
                    hope you will apply for a scholarship. These funds strengthen our community and we want to be sure
                    you know they are available.</p>
            </div>
            <a href="{{ url('/scholarship') }}" class="btn btn-primary">Application Process</a>
        </div>
        <div class="card">
            <img class="card-img-top img-fluid" src="/images/calculator@half.jpg"
                 alt="Hanna Davis, Brochure Editor"/>
            <div class="card-body">
                <h4 class="card-title">
                    Camp Cost Calculator
                </h4>
                <p class="card-text">Use this helpful tool to help estimate how much MUUSA will cost this year. Please
                    consider sharing a room with as many others as possible to reduce your cost and make optimum use of
                    housing. Full details can be found in the brochure.</p>
            </div>
            <a href="{{ url('/cost') }}" class="btn btn-primary">Full-Week Rates</a>
        </div>
    </div>

    <div id="renewed" class="p-4 py-lg-5 text-center mt-3">
        <h2 class="display-4 text-white m-0">&quot;See you next week!&quot;</h2>
        <h5 class="p-5 text-white lead">Where you are welcomed to a warm and loving community.
            Where children are safe and cared for.<br/>
            Where you'll always be accepted.
            Where others share your values.<br/>
            Where your spirit will be renewed!</h5>
    </div>


    <div class="section bg-light" id="testimonial">
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
                            <p class="text-muted mb-0">" I love that I started the week not knowing anyone except my
                                children, but ended the week with lifelong friends. "</p>
                        </div>

                        <div class="p-4">
                            <div>
                                <h4 class="font-16 mb-1">Geeta P.</h4>
                                <p class="text-muted mb-2">
                                    <small> - Colorado Springs, CO</small>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="testi-box mt-4">
                        <div class="testi-desc p-4">
                            <p class="text-muted mb-0">" MUUSA is a true community for building meaningful friendships--
                                as well as a low stress family vacation where you are not always reaching for your
                                wallet.
                                "</p>
                        </div>

                        <div class="p-4">
                            <div>
                                <h4 class="font-16 mb-1">Roger E.</h4>
                                <p class="text-muted mb-2">
                                    <small> - Atlanta, GA</small>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="testi-box mt-4">
                        <div class="testi-desc p-4">
                            <p class="text-muted mb-0">" MUUSA gives me a space to deepen family bonds and recharge
                                connections with my inner humanity. "</p>
                        </div>

                        <div class="p-4">
                            <div>
                                <h4 class="font-16 mb-1">Greg R.</h4>
                                <p class="text-muted mb-2">
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