@extends('layouts.app')
@section('title')
    Payment Information
@endsection

@section('heading')
    Check back here to see your up-to-date billing statement, and mail via check or send payment online via PayPal.
@endsection

@section('content')
    @include('includes.steps')
    <div class="container">
        <form id="muusapayment" class="form-horizontal" role="form" method="POST" action="{{ route('payment.store') }}">
            @include('includes.flash')

            @if(count($years) > 1)
                <ul id="nav-tab-years" class="nav nav-tabs" role="tablist">
                    @foreach($years as $thisyear => $charges)
                        <li class="nav-item{{ $loop->first ? ' pl-5' : '  ml-2' }}">
                            <a class="nav-link{{ $loop->last ? ' active' : '' }}" data-toggle="tab"
                               href="#year-{{ $thisyear }}" role="tab">
                                {{ $thisyear }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
            <div id="nav-tab-yearContent" class="tab-content p-3">
                @foreach($years as $thisyear => $charges)
                    <div role="tabpanel" class="tab-pane fade{{ $loop->last ? ' active show' : '' }}"
                         aria-expanded="{{ $loop->last ? 'true' : 'false' }}" id="year-{{ $thisyear }}">

                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Charge Type</th>
                                <th class="text-right">Amount</th>
                                <th class="text-md- center">Date</th>
                                <th>Memo</th>
                            </tr>
                            </thead>
                            @foreach($charges as $charge)
                                <tr>
                                    <td>{{ $charge->chargetypename }}</td>
                                    <td class="amount" align="right">{{ number_format($charge->amount, 2) }}</td>
                                    <td class="text-md-center">{{ $charge->timestamp }}</td>
                                    <td>{{ $charge->memo }}</td>
                                </tr>
                            @endforeach
                            @if(!session()->has('camper_id'))
                                <tr>
                                    <td>
                                        <label for="donation" class="control-label">Donation</label>
                                    </td>
                                    <td class="form-group @error('donation') has-danger @enderror">
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text">$</span>
                                            </div>
                                            <input type="number" id="donation"
                                                   class="form-control @error('donation') is-invalid @enderror"
                                                   step="any" name="donation" data-number-to-fixed="2" min="0"
                                                   placeholder="Enter Donation Here"
                                                   value="{{ old('donation') }}"/>
                                        </div>

                                        @error('donation')
                                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                                        @enderror
                                    </td>
                                    <td colspan='2'>Please consider at least a $10.00 donation to the MUUSA Scholarship
                                        fund.
                                    </td>
                                </tr>
                            @endif
                            @if($year->is_accept_paypal && !session()->has('camper_id'))
                                <tr class="text-md-right">
                                    <td><strong>Amount Due Now:</strong></td>
                                    <td class="text-right">
                                        <span id="amountNow">{{ number_format(max($deposit, 0), 2) }}</span>
                                    </td>
                                    <td colspan="2"></td>
                                </tr>
                            @endif
                            @if(!empty($housing) || session()->has('camper_id'))
                                <tr class="text-md-right">
                                    <td><strong>Amount Due Upon Arrival:</strong></td>
                                    <td class="text-right">
                                            <span id="amountArrival">
                                                {{ number_format(max(0, $charges->sum('amount')), 2) }}
                                            </span>
                                    </td>
                                    <td colspan="2">&nbsp;</td>
                                </tr>
                            @endif

                            @if($loop->last && session()->has('camper_id') && Gate::allows('is-super'))
                                <tfoot>
                                <tr>
                                    <td class="form-group @error('chargetype_id') has-danger @enderror">
                                        <label for="newchargetypeid" class="sr-only">Chargetype</label>
                                        <select class="form-control @error('chargetype_id') is-invalid @enderror"
                                                id="newchargetypeid" name="chargetype_id">
                                            @foreach($chargetypes as $chargetype)
                                                <option value="{{ $chargetype->id }}"
                                                    {{ $chargetype->id == old('chargetype_id') ? ' selected' : '' }}>
                                                    {{ $chargetype->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="form-group @error('amount') has-danger @enderror">
                                        <label for="amount" class="sr-only">Amount</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text">$</span>
                                            </div>
                                            <input type="text" id="amount"
                                                   class="form-control @error('amount') is-invalid @enderror"
                                                   name="amount" value="{{ old('amount') }}"/>
                                        </div>
                                    </td>
                                    <td class="form-group @error('date') has-danger @enderror">
                                        <label for="date" class="sr-only">Timestamp</label>
                                        <div class="input-group date" data-provide="datepicker"
                                             data-date-format="yyyy-mm-dd" data-date-autoclose="true">
                                            <input id="date" type="text"
                                                   class="form-control @error('date') is-invalid @enderror"
                                                   name="date" value="{{ old('date') }}">
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="far fa-calendar"></i></span>
                                            </div>
                                            <div class="input-group-addon">
                                            </div>
                                        </div>
                                    </td>
                                    <td class="form-group @error('memo') has-danger @enderror" colspan="2">
                                        <label for="memo" class="sr-only">Memo</label>
                                        <input id="memo" class="form-control" name="memo"
                                               value="{{ old('memo') }}">
                                    </td>
                                </tr>
                                </tfoot>
                            @endif

                        </table>
                        @if($loop->last && session()->has('camper_id') && Gate::allows('is-super'))
                            @include('includes.formgroup', ['type' => 'submit', 'label' => '', 'attribs' => ['name' => 'Save Changes']])
                        @endif
                        @if(!session()->has('camper_id'))
                            @if($year->is_accept_paypal)
                                <div class="row p-7">
                                    <div class="col-md-6">
                                        <h4>To Register via Mail:</h4>
                                        Make checks payable to <strong>MUUSA, Inc.</strong><br/>
                                        Mail check by May 31, {{ $year->year }} to<br/>
                                        MUUSA, Inc.<br/>6348 Meis Avenue<br/>
                                        Cincinnati, OH 45224<br/> <br/>
                                    </div>
                                    <div class="col-md-6">
                                        <h4>To Register via PayPal:</h4>
                                        <div class="form-group row @error('amount') has-danger @enderror">
                                            <label for="amount" class="control-label">Payment:</label>

                                            <div class="input-group">
                                                <div class="input-group-prepend"><span class="input-group-text">$</span>
                                                </div>
                                                <input type="number" id="amount" name="amount"
                                                       class="form-control @error('amount') is-invalid @enderror"
                                                       data-toggle="tooltip" title="Or enter another amount..."
                                                       value="{{ number_format(max($charges->sum('amount'), 0), 2, '.', '') }}"/>
                                            </div>

                                            <div class="input-group pt-3">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" id="addthree" name="addthree"> Add 3% to
                                                        my payment
                                                        to
                                                        cover the PayPal service fee
                                                    </label>
                                                </div>
                                            </div>

                                            @error('amount')
                                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                                            @enderror
                                        </div>
                                        <input type="hidden" id="orderid" name="orderid"/>
                                        <input type="hidden" id="address1" name="address1"/>
                                        <input type="hidden" id="address2" name="address2"/>
                                        <input type="hidden" id="city" name="city"/>
                                        <input type="hidden" id="province" name="province"/>
                                        <input type="hidden" id="zipcd" name="zipcd"/>
                                        <div id="paypal-button"></div>
                                    </div>
                                    @else
                                        Please bring payment to the first day of camp on {{ $year->checkin }}. While we
                                        do
                                        accept VISA, Mastercard, Discover, we prefer a check, to minimize fees.
                                    @endif
                                </div>
                            @endif
                    </div>
                @endforeach
            </div>
        </form>
    </div>

    @if(!session()->has('camper_id'))
        <!-- Modal -->
        <div class="modal fade" id="modal-newreg" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">You're Registered for {{ $year->year }}!</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid px-0">
                            <div class="row">
                                You just completed your registration, but there's plenty more to do.
                                Please visit the below pages to make all the changes you need to ensure your camp
                                experience is a great one.

                                <a href="{{ route('household.index') }}"
                                   class="btn btn-link btn-outline-primary btn-block mt-3">
                                    <i class="far fa-home fa-fw"></i> Billing Information</a>
                                <a href="{{ route('campers.index') }}"
                                   class="btn btn-link btn-outline-primary btn-block">
                                    <i class="far fa-users fa-fw"></i> Camper List</a>
                                <a href="#" class="btn disabled btn-outline-primary btn-block">
                                    <i class="far fa-usd-square fa-fw"></i> Statement</a>
                                @if(!$year->is_live)
                                    <h5 class="text-muted mt-4">Opens {{ $year->brochure_date }}</h5>
                                    <a href="#" class="btn disabled btn-outline-primary btn-block">Workshop List</a>
                                    <a href="#" class="btn disabled btn-outline-primary btn-block">Room
                                        Selection</a>
                                    <a href="#" class="btn disabled btn-outline-primary btn-block">Nametags</a>
                                    <a href="#" class="btn disabled btn-outline-primary btn-block">Medical
                                        Responses</a>
                                @else
                                    <a href="{{ route('workshopchoice.index') }}" type="button"
                                       class="btn btn-outline-primary btn-block">
                                        <i class="far fa-rocket fa-fw"></i>Workshop Preferences</a>
                                    <a href="{{ route('roomselection.index') }}" type="button"
                                       class="btn btn-outline-primary btn-block">
                                        <i class="far fa-bed fa-fw"></i> Room Selection</a>
                                    <a href="{{ route('nametag.index') }}" type="button"
                                       class="btn btn-outline-primary btn-block">
                                        <i class="far fa-id-card fa-fw"></i> Nametags</a>
                                    <a href="#" type="button" class="btn btn-outline-primary btn-block">
                                        <i class="far fa-envelope fa-fw"></i> Medical Responses</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    @endif

@endsection

@section('script')
    @if($year->is_accept_paypal)
        <script src="https://www.paypal.com/sdk/js?client-id={{ env('PAYPAL_CLIENT') }}"></script>
        <script>
            @if(session()->has('newreg'))
            $("div#modal-newreg").modal('show');
            @endif
            $(document).on('change', '#donation', function () {
                var total = parseFloat($(this).val());
                $("#amount").val(Math.max(0, parseFloat($("#amountNow").text().replace('$', '')) + total).toFixed(2));
                $("td.amount").each(function () {
                    total += parseFloat($(this).text().replace('$', ''));
                });
                $("#amountArrival").text(Math.max(0, total).toFixed(2));
            });

            paypal.Buttons({
                locale: 'en_US',
                style: {
                    size: 'responsive',
                    color: 'gold',
                    shape: 'pill',
                    label: 'pay',
                    tagline: 'true',
                    fundingicons: 'true',
                    layout: 'horizontal'
                },

                createOrder: function (data, actions) {
                    var amt = parseFloat($("#amount").val());
                    if ($('input#addthree').is(':checked')) amt *= 1.03;
                    if (amt < 0) amt *= -1;

                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                value: amt.toFixed(2)
                            }
                        }]
                    });
                },

                commit: false,
                onApprove: function (data, actions) {
                    return actions.order.capture().then(function (details) {
                        if (details.purchase_units.length > 0) {
                            $("#orderid").val(details.id);
                            $("#amount").val(details.purchase_units[0].amount.value);
                            $("#address1").val(details.purchase_units[0].shipping.address.address_line_1);
                            $("#address2").val(details.purchase_units[0].shipping.address.address_line_2);
                            $("#city").val(details.purchase_units[0].shipping.address.admin_area_2);
                            $("#province").val(details.purchase_units[0].shipping.address.admin_area_1);
                            $("#zipcd").val(details.purchase_units[0].shipping.address.postal_code);

                        }
                        $("form#muusapayment").submit();
                    });
                }
            }).render('#paypal-button');
        </script>
    @endif

@endsection
