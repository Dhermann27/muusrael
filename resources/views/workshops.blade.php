@extends('layouts.app')

@section('title')
    Workshop List
@endsection

@section('heading')
    This page contains a list of the workshops
    @if($year->is_live)
        we have on offer in {{ $year->year }}, grouped by timeslot.
    @else
        we had on offer in {{ $year->year }}, as an example of what might be available.
    @endif
@endsection

@section('image')
    url('/images/workshops.jpg')
@endsection

@section('content')
    <div class="container px-3 py-5 px-lg-4 py-lg-6 bg-grey mb-5">
        @foreach($timeslots->first()->workshops->where('year_id', $year->id) as $workshop)
            @component('components.blog', ['title' => $workshop->name])

                @include('includes.filling', ['workshop' => $workshop])

                <div class="lead d-block">Led by {{ $workshop->led_by }}
                    / {{ $workshop->extra }}
                    @if($workshop->fee > 0)
                        / Fee: ${{ $workshop->fee }}
                    @endif
                </div>

                <p>{{ $workshop->blurb }}</p>
            @endcomponent
        @endforeach
    </div>
@endsection
