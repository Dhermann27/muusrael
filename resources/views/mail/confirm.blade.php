@extends('layouts.email')

@section('subject')
    MUUSA {{ $year->year }} Registration Confirmation
@endsection

@section('content')
    <p>Hello MUUSA Friends,</p>

    <p>Thank you for submitting your registration! We are looking forward to seeing you "next week".</p>

    <p>You have successfully registered the following campers for MUUSA {{ $year->year }}:</p>
    <ul>
        @foreach($campers as $camper)
            <li>{{ $camper->firstname }} {{ $camper->lastname }}</li>
        @endforeach
    </ul>

    <p>As a registered camper, you will have access to the Zoomsa page where you will find all of the current
        information and links: <a href="https://muusa.org/zoomsa">https://muusa.org/zoomsa</a>. Here are a few links to
        get you started on all of the Zoomsa fun!</p>


    <p>Attend the Virtual Awesome Choir rehearsal with Pam Blevins Hinkle on April 10 @ 10am CDT<br/>

        <a href="https://us02web.zoom.us/j/81271766995?pwd=b0VLSWQxOElYU3dTY3ZPRHBURkRiQT09">https://us02web.zoom.us/j/81271766995?pwd=b0VLSWQxOElYU3dTY3ZPRHBURkRiQT09</a><br/>

        Meeting ID: 812 7176 6995<br/>

        Passcode: awesome</p>

    <p>Submit a video greeting or farewell which will be used in our Opening/Closing Celebrations by June 1: <a
            href="https://forms.gle/wWrgiBL4Xr6ZL7hi6">https://forms.gle/wWrgiBL4Xr6ZL7hi6</a></p>

    <p>Sign up for Coffee House by June 30: <a href="https://forms.gle/XBxde4FEKpq9HnBe8">https://forms.gle/XBxde4FEKpq9HnBe8</a></p>

    <p>We look forward seeing you next week!</p>

    <p>Adrienne Cruise<br/>MUUSA Registar</p>
@endsection
