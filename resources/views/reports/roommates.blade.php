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
                <td>{{ !empty($camper->room_id) ? $camper->buildingname . ' ' . $camper->room_number : 'Unassigned' }}</td>
                <td>
                    @include('includes.admin.controls', ['id' => $camper->id])
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection

