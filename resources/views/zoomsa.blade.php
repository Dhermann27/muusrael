@extends('layouts.app')

@section('content')

    <div class="pt-4 pt-md-6 bg-white">
        <div class="container w-lg-70">

            <div class="row my-5 justify-content-center">
                <div class="col-md-8">
                    <p class="text-center mt-5">
                        <a href="{{ route('brochure') }}">
                            <img src="/images/zoomsa.png" alt="Welcome to Zoomsa 2021!"/>
                        </a>
                    </p>
                    <p>Welcome to the Zoomsa information page! Bookmark this in order to access all of the current
                        information about our virtual camp. We will continue to update this page with links and
                        information through the end of camp on July 10.</p>

                    <h5>Virtual Awesome Choir</h5>
                    <p>Pam Blevins Hinkle led a virtual choir rehearsal on April 10. View the recording of the rehearsal
                        here: <a
                            style="color: #007bff; text-decoration: underline;"
                            href="https://tinyurl.com/AwesomeRehearsal">https://tinyurl.com/AwesomeRehearsal</a></p>

                        <p>Ready to make your video for our Virtual Awesome Choir? Instructions can be found here: <a
                            style="color: #007bff; text-decoration: underline;"
                            href="https://tinyurl.com/AwesomeChoirInstructions">https://tinyurl.com/AwesomeChoirInstructions</a>
                    </p>
                    <p>Submit a Virtual Awesome Choir video by June 1: <a
                            style="color: #007bff; text-decoration: underline;"
                            href="https://forms.gle/YqupFGgdQpM3BEmN9">https://forms.gle/YqupFGgdQpM3BEmN9</a>
                    </p>

                    <h5>Opening/Closing Videos</h5>
                    <p>Submit a video greeting or farewell which will be used in our Opening/Closing Celebrations by
                        June 1: <a style="color: #007bff; text-decoration: underline;"
                                   href="https://forms.gle/wWrgiBL4Xr6ZL7hi6">https://forms.gle/wWrgiBL4Xr6ZL7hi6</a>
                    </p>

                    <h5>Coffee House</h5>
                    <p>Sign up for Coffee House by June 30: <a style="color: #007bff; text-decoration: underline;"
                                                               href="https://forms.gle/XBxde4FEKpq9HnBe8">https://forms.gle/XBxde4FEKpq9HnBe8</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
