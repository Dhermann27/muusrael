@extends('layouts.app')

@section('title')
    Guest Speakers: Dr. Glen Thomas Rideout, Rev. Misha Sanders, and Rev. Keith Kron
@endsection

@section('heading')
    Learn more about the {{ $year->year }} guest speakers.
@endsection

@section('image')
    url('/images/biographies.jpg')
@endsection

@section('content')
    <div class="container px-3 py-5 px-lg-10 py-lg-6 bg-grey mb-5">
        @component('components.blog', ['title' => 'Theme Speaker'])
            <p>
                <img class="float-right p-3" src="/images/drglensm.jpg"
                     alt="Dr. Glen Thomas Rideout" data-no-retina/>
                <strong>Dr. Glen Thomas Rideout</strong> joined First Universalist of Minneapolis as Director of Worship
                Arts Ministries in August 2021. Prior to that, he served the Unitarian Universalist Congregation of Ann
                Arbor, Michigan, in a variety of leadership roles since 2007, including most recently as Director of
                Worship and Music. Known for his deeply collaborative creation of worship services for congregations and
                national gatherings, Glen Thomas teaches worship design at Meadville Lombard Theological School and is
                the author of the curriculum, De-Centering Whiteness in Worship, with Julica Hermann de la Fuente and
                Rev. Erika Hewitt. Glen Thomas holds a Doctorate of Musical Arts in Conducting from the University of
                Michigan, and prior to the pandemic, traveled internationally to support the choir of his youth in its
                ensemble tours.
            </p>
        @endcomponent
    </div>
@endsection
