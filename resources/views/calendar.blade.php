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
                        backgroundColor: '#b3dc6c',
                        borderColor: '#93c00b',
                        textColor: '#1d1d1d'
                    },
                    {
                        googleCalendarId: 'sordeheb5g4maoinve706b147o@group.calendar.google.com',
                        backgroundColor: '#337ab7',
                        borderColor: '#373ad7',
                        textColor: '#1d1d1d'
                    },
                    {
                        googleCalendarId: 'lpuoked7q3msijpdeqqnkvanb8@group.calendar.google.com',
                        backgroundColor: '#d62b61',
                        borderColor: '#7a1538',
                        textColor: '#1d1d1d'
                    }
                ]
            });
            calendar.render();
        });
    </script>
@endsection
