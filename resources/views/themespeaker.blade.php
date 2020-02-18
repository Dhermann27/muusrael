@extends('layouts.app')

@section('title')
    Theme Speaker:<br />Dr. Glen Thomas Rideout
@endsection

@section('heading')
    Learn more about the {{ $year->year }} theme speaker.
@endsection

@section('image')
    url('/images/biographies.jpg')
@endsection

@section('content')
    <div class="container px-3 py-5 px-lg-10 py-lg-6 bg-grey mb-5">
        @component('components.blog', ['title' => '2020 Theme Speaker'])
            <p>
                <img class="float-right p-3" src="/images/drglensm.jpg"
                     alt="Dr. Glen Thomas Rideout" data-no-retina/>
                <span class="dropcap-lg tint-bg">I</span>n
                an address to the Unitarian Universalist Ministers Association, The Rev. Otis Moss III, senior
                pastor of the 8,000-member Trinity United Church of Christ, named him &quot;without a doubt one of
                the greatest ministers and gifts to this nation.&quot; Artist, theologian, teacher Dr. Glen Thomas
                Rideout's is among Unitarian Universalism's most prolific worship leaders, an influential voice in
                worship and music craft in a season of fundamental culture shift faith-wide.</p>
            <p style="margin-bottom: 50%">
                As Director of Worship and Music for First Unitarian Universalist Congregation of Ann Arbor, Dr.
                Glen Thomas Rideout leads the uncommonly-collaborative, team worship planning process he
                created with the Rev. Gail Ruth Geisenhainer (GUISE-in-hayner) in 2014. During his tenure,
                Ann Arbor’s worship and music ministries risen to a denominational leader in congregational
                singing innovation, ensemble spiritual practice, and liturgical imagination.</p>
    @endcomponent
@endsection
