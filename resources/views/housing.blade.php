@extends('layouts.app')

@section('title')
    Housing Options
@endsection

@section('heading')
    Check out all the available room types we have in the wonderful YMCA of the Ozarks facilities!
@endsection

@section('image')
    url('/images/housing.jpg')
@endsection

@section('content')
    <div class="container px-3 py-5 px-lg-4 py-lg-6 bg-grey mb-5">
        @foreach($buildings as $building)
            @component('components.blog', ['title' => $building->name])

                <div class="mt-2">{!! $building->blurb !!}</div>

                @if(isset($building->image))
                    <div id="carousel{{ $building->id }}" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            @foreach($building->image_array as $image)
                                <li data-target="#carousel{{ $building->id }}" data-slide-to="{{ $loop->index }}"
                                    @if($loop->first) class="active" @endif></li>
                            @endforeach
                        </ol>
                        <div class="carousel-inner">
                            @foreach($building->image_array as $image)
                                <div class="carousel-item @if($loop->first) active @endif">
                                    <img src="/images/buildings/{{ $image }}" alt="Image of {{ $building->name }} room"
                                         class="d-block w-100"/>
                                </div>
                            @endforeach
                        </div>
                        <a class="carousel-control-prev" href="#carousel{{ $building->id }}" role="button"
                           data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carousel{{ $building->id }}" role="button"
                           data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                @endif
            @endcomponent
        @endforeach
    </div>
@endsection
