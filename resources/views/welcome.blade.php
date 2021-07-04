@extends('layouts.app')

@section('css')
    <style>
        .video-responsive {
            overflow: hidden;
            padding-bottom: 56.25%;
            position: relative;
            height: 0;
        }
        .video-responsive iframe {
            left: 0;
            top: 0;
            height: 100%;
            width: 100%;
            position: absolute;
        }
    </style>
@endsection

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
                <h2>ZOOMSA 2021</h2>
            </div>
            <div class="row my-5">
                <div class="col-md-6">
                    <h4>Hello Campers!</h4>


                                        <div class="video-responsive">
                                            <iframe width="560" height="315"
                                                    src="https://www.youtube.com/embed/QYAIFoV_JVE?controls=0"
                                                    frameborder="0"
                                                    allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                                                    allowfullscreen></iframe>
                                        </div>

                </div>

                <div class="col-md-6 text-sm-center">
                    <h5 class="pt-3">Help Zoomsa</h5>
                    <div class="text-sm-left">Even though MUUSA 2021 was cancelled, we still incurred a range of
                        expenses for the year, but no revenue to offset them. If you're enjoying Zoomsa, please
                        consider a donation.
                    </div>
                    <label for="muusa-donation" class="control-label sr-only">Zoomsa Donation Amount</label>
                    <div id="muusa-donation-group" class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text">$</span></div>
                            <input type="number" id="muusa-donation" class="form-control" step="any"
                                   data-number-to-fixed="2" min="0" placeholder="Enter Donation Here"/>
                        </div>
                    </div>
                    <div id="minimuusa-donate-button"></div>
                    <div id="muusa-donate-message" class="d-none text-muted">Thank you for your donation. <i
                            class="fas fa-hands-heart"></i></div>

                    <h5 class="pt-5">Log your MUUSA Miles</h5>
                    <a href="{{ route('miles') }}"
                       class="mb-1 py-2 px-4 btn btn-primary btn-shadow btn-flat btn-sm btn-bold text-uppercase text-letter-spacing rounded-0">
                        Get MUUving!
                    </a>

                </div>
            </div>
        </div>
    </div>

    <div class="pt-4 pt-md-6 bg-white">
        <div class="container w-lg-70">
            <div class="row mt-2">
                <div class="col-lg-6 d-sm-flex">
                    <a href="{{ route('brochure') }}">
                        <img id="brochureimg" class="card-img-top" src="/images/brochure.png"
                             alt="Web Brochure cover" data-no-retina>
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
                                Planning Council. It has it all: workshop descriptions, daily schdules, frequently
                                asked questions, and more.
                            @else
                                While you can register right now to reserve your spot, our Planning Council is working
                                diligently to prepare this year's brochure, which should be ready on February 1. You can
                                currently see last year's to get an idea of what it might contain.
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
            <a href="{{ route('themespeaker') }}" class="btn btn-primary">Guest Speaker Biographies</a>
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

@section('script')
    <script
        src="https://www.paypal.com/sdk/js?client-id={{ env('PAYPAL_CLIENT') }}&disable-funding=credit,card"></script>
    <script type="text/javascript">
        paypal.Buttons({
            locale: 'en_US',
            style: {
                size: 'responsive',
                color: 'gold',
                shape: 'pill',
                label: 'paypal',
                vault: 'false',
                layout: 'vertical'
            },
            createOrder: function (data, actions) {
                $(".has-danger").removeClass("has-danger");
                $(".is-invalid").removeClass("is-invalid");
                $(".invalid-feedback").remove();
                var amt = parseFloat($("input#muusa-donation").val());
                if (isNaN(amt) || amt <= 0.0) {
                    var group = $("div#muusa-donation-group").addClass("has-danger");
                    group.find("input").addClass('is-invalid');
                    group.find("div:first").append("<span class=\"invalid-feedback\"><strong>Please set a donation amount</strong></span>");
                    $("span.invalid-feedback").show();
                    return false;
                }
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: parseFloat($("input#muusa-donation").val()).toFixed(2)
                        },
                        description: 'Thank you for your donation to Zoomsa 2021',
                        custom_id: 'minimuusa2021'
                    }]
                });
            },
            onApprove: function (data, actions) {
                return actions.order.capture().then(function (details) {
                    $("div#muusa-donation-group").hide();
                    $("div#minimuusa-donate-button").hide();
                    $("div#muusa-donate-message").removeClass("d-none");
                });
            }
        }).render('#minimuusa-donate-button');
    </script>
@endsection
