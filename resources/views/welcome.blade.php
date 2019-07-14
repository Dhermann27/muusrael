@extends('layouts.app')

@section('content')
    <div class="content">
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
@endsection