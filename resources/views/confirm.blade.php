@extends('layouts.appstrap')

@section('css')
    <link rel="stylesheet" href="/css/print.css" type="text/css" media="print"/>
@endsection

@section('title')
    MUUSA Confirmation Letter
@endsection

@section('heading')
    Welcome to MUUSA {{ $year->year }}!
@endsection

@section('content')
    @if(count($families) == 1)
        @include('snippet.steps', ['steps' => $steps[0]])
    @endif
    @foreach($families as $family)
        <div class="text-center">
            <img src="/images/print_logo.png" alt="Welcome to MUUSA {{ $year->year }}!"/>
        </div>
        <p>&nbsp;</p>
        <h3>{{ $family->family_name }}<br/>
            {{ $family->address1 }}<br/>
            @if(!empty($family->address2))
                {{ $family->address2 }}<br/>
            @endif
            {{ $family->city }}, {{ $family->state_code }} {{ $family->zipcd }}</h3>
        <p>&nbsp;</p>
        <table class="table">
            <thead>
            <tr>
                <th colspan="8"><strong>Camper Information</strong></th>
            </tr>
            <tr>
                <th>Name</th>
                <th>Preferred Pronoun</th>
                <th>Email Address</th>
                <th>Phone Number</th>
                <th>Birthdate</th>
                <th>Program</th>
                <th>Church</th>
                <th>Assigned Room</th>
            </tr>
            </thead>
            <tbody>
            @foreach($family->campers as $camper)
                <tr>
                    <td>{{ $camper->firstname }} {{ $camper->lastname }}</td>
                    <td>{{ $camper->pronounname }}</td>
                    <td>{!! !empty($camper->email) ? $camper->email : '&nbsp;' !!}</td>
                    <td>{!! !empty($camper->phonenbr) ? $camper->formatted_phone : '&nbsp;' !!}</td>
                    <td>{{ $camper->birthday }}</td>
                    <td>{{ $camper->programname }}</td>
                    <td>{{ $camper->churchname }}</td>
                    <td>{{ $camper->buildingname }} {{ $camper->roomid != '1170' ? $camper->room_number : ''}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <p>&nbsp;</p>
        <table class="table">
            <thead>
            <tr>
                <th colspan="4"><strong>Statement</strong></th>
            </tr>
            <tr>
                <th>Charge Type</th>
                <th align="right">Amount</th>
                <th align="center">Date</th>
                <th>Memo</th>
            </tr>
            </thead>
            <tbody>
            @foreach($family->charges()->orderBy('timestamp')->orderBy('amount', 'desc')->get() as $charge)
                <tr>
                    <td>{{ $charge->chargetypename }}</td>
                    <td class="amount" align="right">${{ number_format($charge->amount, 2) }}</td>
                    <td align="center">{{ $charge->timestamp }}</td>
                    <td>{{ $charge->memo }}</td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <td colspan="3" align="right"><strong>Amount Due on {{ $year->start_date }}:</strong></td>
                <td>${{ number_format( $family->charges->sum('amount'), 2) }}</td>
            </tr>
            </tfoot>
        </table>
        <p>&nbsp;</p>
        @if(count($families) == 1)
            <table class="table">
                <thead>
                <tr>
                    <th colspan="5"><strong>Workshop Signups</strong></th>
                </tr>
                <tr>
                    <th>Name</th>
                    <th>Workshop</th>
                    <th>Timeslot</th>
                    <th>Days</th>
                    <th>Location</th>
                </tr>
                </thead>
                <tbody>
                @foreach($family->campers as $camper)
                    @foreach($camper->yearattending->workshops()->get() as $signup)
                        <tr>
                            <td>{{ $camper->firstname }} {{ $camper->lastname }}</td>
                            <td>{{ $signup->workshop->name }}</td>
                            @if($signup->is_enrolled=='1')
                                <td>{{ $signup->workshop->timeslot->name }}</td>
                                <td>{{ $signup->workshop->display_days }}</td>
                                <td>{{ $signup->workshop->room->room_number }}</td>
                            @else
                                <td colspan="3" align="center">
                                    <i>Waiting List</i>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                @endforeach
                </tbody>
            </table>
        @endif

        <footer style="text-align: center;"><h4>See you next week!</h4></footer>

        @if(count($families) == 1)
            @component('snippet.accordion', ['id' => $family->id])
                @foreach($family->campers()->where('age', '<', '18')->get() as $camper)
                    @component('snippet.accordioncard', ['id' => $family->id, 'loop' => $loop, 'heading' => $camper->id,
                        'title' => $camper->firstname . ' ' . $camper->lastname, 'closed' => $camper->medicalresponse])
                        @slot('badge')
                            @if($camper->medicalresponse)
                                <span class="badge badge-primary">
                                <i class="far fa-check" title="Medical Response Submitted"></i>
                            </span>
                            @endif
                        @endslot
                        @if(count($families) == 1 && !empty($camper->program->letter))
                            {!! $camper->program->letter !!}
                        @endif

                        <div class="container">
                            <form class="form-horizontal medicalresponse" role="form" method="POST"
                                  action="{{ url('/confirm') . '/y/' . $camper->yearattendingid }}">
                                @include('snippet.flash')

                                @include('snippet.medical', ['camper' => $camper, 'first' => $loop->first])

                                @if(!isset($readonly) || $readonly === false)
                                    <div class="form-group row d-print-none">
                                        <label for="submit" class="col-md-4 control-label">&nbsp;</label>
                                        <div class="col-md-6">
                                            <div class="text-lg-right">
                                                @if($camper->medicalresponse)
                                                    <button class="btn btn-lg btn-success submit py-3 px-4"><i
                                                            class="far fa-check"></i> Saved
                                                    </button>
                                                @else
                                                    <button class="btn btn-lg btn-primary submit py-3 px-4">Save
                                                        Response
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </form>
                        </div>
                    @endcomponent
                @endforeach
            @endcomponent
            <footer>Please print this page using your browser's print (Ctrl+P or &#8984+P) function. Regardless
                of appearance on your screen, forms will be paginated correctly.
            </footer>
        @endif
    @endforeach
@endsection

@section('script')
    <script src="/js/bootstrap-datepicker.min.js"></script>
    <script>
        $("button.copyanswers").on('click', function (e) {
            e.preventDefault();
            var myform = $(this).parents("form");
            var first = $("form.medicalresponse").first();
            var elements = myform.find("input, textarea, select");
            if (first.find('select[name*="is_insured"]').val() === '1') {
                myform.find("div.insurance").removeClass("d-none");
            }
            first.find("input, textarea, select").each(function (index) {
                if ($(this).attr("type") !== "checkbox") {
                    elements[index].value = $(this).val();
                } else {
                    elements[index].checked = $(this).checked;
                }
            });
            return false;
        });

        $('form.medicalresponse select[name*="is_insured"]').on('change', function (e) {
            if ($(this).val() === '1') {
                $(this).parent().parent().next().removeClass("d-none");
            } else {
                $(this).parent().parent().next().addClass("d-none");
            }
        });


        $('form.medicalresponse button.submit').on('click', function (e) {
            e.preventDefault();
            var form = $(this).parents("form");
            $(this).val("Saving").removeClass("btn-primary btn-danger").prop("disabled", true);
            form.find(".has-danger").removeClass("has-danger");
            form.find(".is-invalid").removeClass("is-invalid");
            form.find(".invalid-feedback").remove();
            $("div.alert").remove();
            $.ajax({
                url: form.attr("action"),
                type: 'post',
                data: form.serialize(),
                async: false,
                success: function (data) {
                    form.before("<div class='alert alert-success'>" + data + "</div>");
                    form.find("button").val("Saved").addClass("btn-success").prop("disabled", false);
                    if (form.parents(".card").next(".card") !== undefined) {
                        form.parents(".card").next(".card").find(".collapse").collapse('show');
                    }
                },
                error: function (data) {
                    if (data.status === 500) {
                        form.before("<div class='alert alert-danger'>Unknown error occurred. Please use the Contact Us form to ask for assistance and include the approximate time you received this message.</div>");
                    } else {
                        var errorCount = data !== undefined ? Object.keys(data.responseJSON.errors).length : '';
                        $.each(data.responseJSON.errors, function (k, v) {
                            var group = $("#" + k).parents(".form-group").addClass("has-danger");
                            group.find("select,input").addClass('is-invalid');
                            group.find("div:first").append("<span class=\"invalid-feedback\"><strong>" + this[0] + "</strong></span>");
                        });
                        $("span.invalid-feedback").show();
                        form.before("<div class='alert alert-danger'>You have " + errorCount + " error(s) in your form. Please adjust your entries and resubmit.</div>");
                    }
                    form.find("button").val("Resubmit").addClass("btn-danger").prop("disabled", false);
                }
            });
        });
    </script>
@endsection

