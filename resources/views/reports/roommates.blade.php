@extends('layouts.app')

@section('title')
    Roommates Report
@endsection

@section('content')
    <table class="table">
        <thead>
        <tr>
            <th>Camper Name</th>
            <th>Roommate Preference</th>
            <th>Current Assignment</th>
            <th>Controls</th>
        </tr>
        </thead>
        <tbody>
        @foreach($campers as $camper)
            <tr>
                <td>{{ $camper->lastname }}, {{ $camper->firstname }}</td>
                <td>{{ $camper->roommate }}</td>
                <td>{{ !empty($camper->roomid) ? $camper->yearattending->room->room_number : 'Unassigned' }}</td>
                <td>
                    @include('admin.controls', ['id' => 'c/' . $camper->id])
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection

