@extends('layouts.app')

@section('title')
    Cognoscenti
@endsection

@section('content')
    <div class="container">
        <table class="table">
            <thead>
            <tr>
                <th>Position</th>
                <th>Name</th>
                <th>Email</th>
                <th>
                    <a class="btn btn-secondary float-right" href="mailto:{{ $positions->flatten(1)->implode('email', ';') }}">
                        Email All <i class="far fa-envelope"></i></a>
                </th>
            </tr>
            </thead>
            <tbody>
            @foreach($pctypes as $pctype)
                <tr class="bg-info">
                    <td colspan="3">{{ $pctype['name'] }}</td>
                    <td><a class="btn btn-secondary btn-sm"
                           href="mailto:{{ $positions[$pctype['id']]->implode('email', ';') }}">
                            Email Group <i class="far fa-envelope"></i></a></td>
                </tr>
                @forelse($positions[$pctype['id']] as $position)
                    <tr>
                        <td>{!! $position->staffpositionname !!}</td>
                        <td>{{ $position->lastname }}, {{ $position->firstname }}</td>
                        <td>{{ $position->email }} <a href="mailto:{{ $position->email }}">
                                <i class="far fa-envelope"></i></a>
                        </td>
                        <td>
                            @include('includes.admin.controls', ['id' => $position->camper_id])
                        </td>
                    </tr>
                @empty
                    <td colspan="4">No positions assigned for this year</td>
                @endforelse
            @endforeach
            </tbody>
        </table>

    </div>
@endsection

