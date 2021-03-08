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
                events: {
                    googleCalendarId: 'dpdspm7d55pr51mag0tp0d2cf0@group.calendar.google.com',
                }
            });
            calendar.render();
        });
    </script>
@endsection
