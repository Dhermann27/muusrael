@extends('layouts.app')

@section('title')
    Bank Deposits
@endsection

@section('content')
    @component('components.navtabs', ['tabs' => $chargetypes, 'id'=> 'id', 'option' => 'name'])
        @foreach($chargetypes as $chargetype)
            <div class="tab-pane fade{!! $loop->first ? ' active show' : '' !!}" id="tab-{{ $chargetype->id }}"
                 role="tabpanel">

                <div class="accordion" id="accordion-{{ $chargetype->id }}">
                    @forelse($chargetype->thisyearcharges->groupBy('deposited_date')->sortKeys() as $deposited_date => $charges)
                        @component('components.accordioncard', ['id' => $deposited_date, 'show' => $loop->first, 'heading' => $deposited_date ? 'Deposited on ' . $deposited_date : 'Undeposited' , 'parent' => $chargetype->id])
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Camper Name</th>
                                    <th>Amount</th>
                                    <th>Timestamp</th>
                                    <th>Memo</th>
                                    <th>Controls</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($charges as $charge)
                                    <tr>
                                        <td>{{ $charge->camper->firstname }} {{ $charge->camper->lastname }}</td>
                                        <td>{{ number_format($charge->amount, 2) }}</td>
                                        <td>{{ $charge->timestamp }}</td>
                                        <td>{{ $charge->memo }}</td>
                                        <td>
                                            {{--                                                @include('admin.controls', ['id' => 'c/' . $charge->camper->id])--}}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="5" class="text-md-right">
                                        Total Deposit: ${{ number_format(abs($charges->sum('amount')), 2) }}
                                    </td>
                                </tr>
                                @if(!$deposited_date && Gate::allows('is-super'))
                                    <tr>
                                        <td colspan="5" class="text-md-right">
                                            <form role="form" method="POST"
                                                  action="{{ route('reports.deposits.mark', ['id' => $chargetype->id]) }}">
                                                @csrf
                                                @include('includes.formgroup', ['type' => 'submit', 'label' => '', 'attribs' => ['name' => 'Mark as Deposited']])
                                            </form>
                                        </td>
                                    </tr>
                                @endif
                                </tfoot>
                            </table>
                        @endcomponent
                    @empty
                        <h3 class="ml-5">No charges found for this chargetype in {{ $year->year }}</h3>
                    @endforelse
                </div>
            </div>
        @endforeach
    @endcomponent
@endsection

