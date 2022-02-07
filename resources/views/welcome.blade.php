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
            <div class="row mt-2">
                <div class="col-lg-6 d-sm-flex">
                    <a href="{{ route('brochure') }}">
                        <img id="brochureimg" class="card-img-top" src="/images/brochure.png"
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
                                diligently to prepare this year's brochure, which should be ready on
                                {{ $year->brochure_date }}. You can currently see last year's to get an idea of what
                                it might contain.
                            @endif
                        </p>
                        <a href="{{ route('brochure') }}"
                           class="mb-1 py-2 px-4 btn btn-primary btn-shadow btn-flat btn-sm btn-bold text-uppercase text-letter-spacing rounded-0">
                            <i class="far fa-file-pdf mr-2"></i> Take a look
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-secondary py-4">
        <div class="container-fluid d-lg-flex">

            <div class="col-lg-8 col-offset-2 d-sm-flex align-content-center d-flex align-items-center">
                <div class="mr-auto py-0 pl-lg-5 my-3 my-md-0">
                    <h2 class="display-4 mt-3 mt-lg-0">
                        <i class="fas fa-shield-virus 3x"></i> COVID-19 Procedures
                    </h2>
                    <p class="line-height-30 py-md-2 op-7">
                        Our goal is to provide a memorable camp experience that is as close to previous years with a new
                        emphasis on safety. All campers who are eligible for vaccination must be fully vaccinated in
                        accordance with CDC guidelines prior to the start of camp in 2022. Vaccines allow us to care for
                        each other and our community. One camper getting sick is one too many, and we are grateful for
                        this opportunity to protect each other.
                    </p>
                    <p>
                        We do not yet know what best practices will be in July and our guidelines on masking, physical
                        distancing, testing, etc. will be made in accordance with evidence-based public health
                        recommendations closer to camp. We will provide these to you by June 1st. Questions should be
                        directed to Jesse Hardin, the Omsbuddy and lead for the PC Covid Task Force using the <a
                            href="{{ route('contact.index') }}">Contact Us</a> form (choose "Omsbuddy").
                    </p>
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
                    Rooms are filling up quickly. <u><a href="{{ route('contact.index') }}" class="text-white">Contact
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
            <img class="card-img-top" src="/images/housing.jpg" alt="The Hill to Trout Lodge"/>
            <div class="card-body">
                <h4 class="card-title">
                    Housing
                </h4>
                <p class="card-text">YMCA of the Ozarks, Trout Lodge, is located on 5,200 acres of pine and oak
                    forest
                    on a private 360-acre lake 75 miles southwest of St. Louis, Missouri, outside of Potosi.
                    Accommodations are available for all budgets.</p>
            </div>
            <a href="{{ route('housing') }}" class="btn btn-primary">Housing Options</a>
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
    </div>

    <div class="card-deck p-3">
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
        <div class="card px-5">
            <img class="card-img-top" src="/images/scholarship.jpg"
                 alt="A hummingbird in dire need of a sugar scholarship"/>
            <div class="card-body">
                <h4 class="card-title">
                    Scholarship Opportunities
                </h4>
                <p class="card-text">If finances are tight and MUUSA doesn't quite fit into your budget this
                    year, we
                    hope you will apply for a scholarship. These funds strengthen our community and we want to
                    be sure
                    you know they are available.</p>
            </div>
            <a href="{{ route('scholarship') }}" class="btn btn-primary">Application Process</a>
        </div>
        <div class="card px-5">
            <img class="card-img-top" src="/images/calculator.jpg"
                 alt="John Sandman, Treasurer"/>
            <div class="card-body">
                <h4 class="card-title">
                    Camp Cost Calculator
                </h4>
                <p class="card-text">Use this helpful tool to help estimate how much MUUSA will cost this year.
                    Please
                    consider sharing a room with as many others as possible to reduce your cost and make optimum
                    use of
                    housing. Full details can be found in the brochure.</p>
            </div>
            <a href="{{ route('cost') }}" class="btn btn-primary">Full-Week Rates</a>
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

