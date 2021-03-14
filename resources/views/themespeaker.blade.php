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
        @component('components.blog', ['title' => 'Guest Speakers for Opening and Closing Celebrations'])
            <p>
                <img class="float-right p-3" src="/images/drglensm.jpg"
                     alt="Dr. Glen Thomas Rideout" data-no-retina/>
                Artist, theologian, teacher <strong>Dr. Glen Thomas Rideout</strong> is among Unitarian Universalism’s most prolific
                worship leaders, an influential voice in worship and music craft in a season of fundamental culture
                shift faith-wide. As Director of Worship and Music for First Unitarian Universalist Congregation of Ann
                Arbor, Rideout leads the uncommonly-collaborative team worship planning process. During his tenure, Ann
                Arbor’s worship and music ministries have risen to be denominational leaders in congregational singing
                innovation, ensemble spiritual practice, and liturgical imagination.
            </p>

            <p>
                <img class="float-left p-3" src="/images/mishasm.jpg"
                     alt="Rev. Misha Sanders" data-no-retina/>
                <strong>Rev. Misha Sanders</strong> is feeling very blessed to be the Senior Minister at Northwest Unitarian Universalist
                Congregation in beautiful Sandy Springs, Georgia. She is a fiery preacher of the good news of Unitarian
                Universalism, and believes that the whole world is built and rebuilt by the stories we tell ourselves
                and each other.
            </p>
        @endcomponent
        @component('components.blog', ['title' => 'Guest Speaker for Midweek Celebration'])
            <p style="margin-bottom: 25%">
                <img class="float-right p-3" src="/images/keithsm.jpg"
                     alt="Rev. Keith Kron" data-no-retina/>
                <strong>Rev. Keith Kron</strong> is the Director of the Transitions Office for the Unitarian Universalist Association,
                helping congregations and ministers as they navigate the ministerial search process. He is the former
                Director of the Office of Bisexual, Gay, Lesbian, and Transgender Concerns for the UUA. He has taught an
                online course for school on children’s literature. He has created a special Harry Potter Jeopardy game
                and is considered by many an expert on Harry Potter.
            </p>
    @endcomponent
@endsection
