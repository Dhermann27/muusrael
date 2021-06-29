@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.5.1/main.min.css">
@endsection

@section('title')
    Personalized Calendar
@endsection

@section('heading')
    See the up-to-date information on everything happening for you and your family during the week of MUUSA!
@endsection

@section('content')
    <div class="container px-3 py-5 px-lg-4 py-lg-6 bg-grey mb-5">
        <p class="text-md-center">The full ZOOMSA schedule, including Zoom links and session descriptions, can be found at <a style="color: #007bff; text-decoration: underline;" href="{{ route('schedule') }}">muusa.org/schedule</a>.<br />
            Click on any of the calendars below to import the events in your own calendar program.</p>
        <div class="row my-5 justify-content-center">
            <div class="col-md-4 text-md-center">
                <h4>Calendar Key</h4>
            </div>
            <div class="col-md-4">
                <p class="text-md-center"><a style="color: #f7c139; text-decoration: underline;" href="https://calendar.google.com/calendar/ical/40c1vpiqem2h4r541rq94vtgqg%40group.calendar.google.com/public/basic.ics">Children &amp; Youth Programming</a></p>
                <p class="text-md-center"><a style="color: #f17d10; text-decoration: underline;" href="https://calendar.google.com/calendar/ical/sordeheb5g4maoinve706b147o%40group.calendar.google.com/public/basic.ics">Intergenerational Programming</a></p>
                <p class="text-md-center"><a style="color: #947bb4; text-decoration: underline;" href="https://calendar.google.com/calendar/ical/46k0e6qr5o3133niqq1fe5kcdc%40group.calendar.google.com/public/basic.ics">Young Adult (YA) Programming</a></p>
            </div>
            <div class="col-md-4">
                <p class="text-md-center"><a style="color: #b23c66; text-decoration: underline;" href="https://calendar.google.com/calendar/ical/lpuoked7q3msijpdeqqnkvanb8%40group.calendar.google.com/public/basic.ics">Worship &amp; Celebration</a></p>
                <p class="text-md-center"><a style="color: #148143; text-decoration: underline;" href="https://calendar.google.com/calendar/ical/muusaworkshops%40gmail.com/public/basic.ics">Adult Sequential Programming</a></p>
                <p class="text-md-center"><a style="color: #40b77d; text-decoration: underline;" href="https://calendar.google.com/calendar/ical/muusaworkshops@gmail.com/public/basic.ics">Adult Standalone Programming</a></p>
            </div>
        </div>
        <h4 class="text-md-center" style="color: darkred">All times are listed in Central Time Zone</h4>
        <div id="calendar"></div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.5.1/main.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                allDaySlot: false,
                googleCalendarApiKey: '{{ env('GOOGLE_API_KEY') }}',
                headerToolbar: {
                    start: '', center: $(window).width() < 768 ? 'prev today next' : '', end: ''
                },
                height: 'auto',
                initialDate: $(window).width() < 768 ? '{{ $year->next_day }}' : '{{  $year->checkin }}',
                initialView: $(window).width() < 768 ? 'timeGridDay' : 'timeGridWeek',
                nowIndicator: true,
                slotMinTime: '07:00:00',
                slotMaxTime: '23:00:00',
                themeSystem: 'bootstrap',
                eventSources: [
                    {
                        googleCalendarId: 'muusaworkshops@gmail.com',
                        backgroundColor: '#148143',
                        borderColor: '#93c00b',
                        textColor: '#1d1d1d'
                    },
                    {
                        googleCalendarId: '40c1vpiqem2h4r541rq94vtgqg@group.calendar.google.com',
                        backgroundColor: '#f7c139',
                        borderColor: '#93c00b',
                        textColor: '#1d1d1d'
                    },
                    {
                        googleCalendarId: '46k0e6qr5o3133niqq1fe5kcdc@group.calendar.google.com',
                        backgroundColor: '#947bb4',
                        borderColor: '#93c00b',
                        textColor: '#1d1d1d'
                    },
                    {
                        googleCalendarId: 'sordeheb5g4maoinve706b147o@group.calendar.google.com',
                        backgroundColor: '#f17d10',
                        borderColor: '#373ad7',
                        textColor: '#1d1d1d'
                    },
                    {
                        googleCalendarId: 'lpuoked7q3msijpdeqqnkvanb8@group.calendar.google.com',
                        backgroundColor: '#b23c66',
                        borderColor: '#7a1538',
                        textColor: '#1d1d1d'
                    }
                ]
            });
            calendar.render();
        });
    </script>
@endsection
