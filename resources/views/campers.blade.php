@extends('layouts.app')

@section('css')
    {{--    <link rel="stylesheet"--}}
    {{--          href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css"/>--}}
    {{--    <link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>--}}
    {{--    <link href="//cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css"--}}
    {{--                    rel="stylesheet"/>--}}
@endsection

@section('title')
    Camper Information
@endsection

@section('heading')
    This page can show you all individual information about the campers in your family, both attending this year
    and returning soon.
@endsection

@section('content')
    include('snippet.steps', ['steps' => $steps[0]])
    <div class="container">
        <form id="camperinfo" class="form-horizontal" role="form" method="POST" action="{{ route('campers.store') }}">
            {{--            . (isset($readonly) && $readonly === false ? '/f/' . $campers[0]->familyid : '')}}">--}}
            include('snippet.flash')

            <ul class="nav nav-tabs" role="tablist">
                @foreach($campers as $camper)
                    <li role="presentation" class="nav-item">
                        <a href="#tab-{{ $camper->id }}" aria-controls="{{ $camper->id }}" role="tab"
                           class="nav-link{!! $loop->first ? ' active' : '' !!}" data-toggle="tab">
                            {{ old('firstname.' . $loop->index, $camper->firstname) }}
                        </a>
                    </li>
                @endforeach
                @if(!isset($readonly) || $readonly === false)
                    <li>
                        <a id="newcamper" class="nav-link" href="#" role="tab" title="Create New Camper"><i
                                class="far fa-plus"></i></a>
                    </li>
                @endif
            </ul>

            <fieldset{{ isset($readonly) && $readonly === true ? ' disabled' : '' }}>
                <div class="tab-content">
                    @foreach($campers as $camper)
                        @include('includes.camper', ['camper' => $camper, 'looper' => $loop->index])
                    @endforeach
                </div>
                @if(!isset($readonly) || $readonly === false)
                    @include('includes.formgroup', ['type' => 'submit', 'label' => '', 'attribs' => ['name' => 'Save Changes']])
                @endif
            </fieldset>
        </form>
    </div>
    <div id="empty">
        @include('includes.camper', ['camper' => $empty, 'looper' => 999])
    </div>
@endsection

@section('script')
    {{--    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script>--}}
    {{--    <script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>--}}
    <script type="text/javascript">

        // $(function () {

        {{--    bind($("form#camperinfo"));--}}

        {{--    $("#newcamper").on('click', function (e) {--}}
        {{--        e.preventDefault();--}}
        {{--        var camperCount = $(".tab-content div.tab-pane").length;--}}
        {{--        $(this).closest('li').before('<li role="presentation" class="nav-item"><a href="#tab-' + camperCount + '" class="nav-link" data-toggle="tab">New Camper</a></li>');--}}
        {{--        var emptycamper = $("div#empty .tab-pane");--}}
        {{--        var empty = emptycamper.clone(false).attr("id", "tab-" + camperCount);--}}
        {{--        empty.find("input, select").each(function () {--}}
        {{--            $(this).attr("id", $(this).attr("id").replace(/\d+$/, camperCount));--}}
        {{--        });--}}
        {{--        empty.find("label").each(function () {--}}
        {{--            $(this).attr("for", $(this).attr("for").replace(/\d+$/, camperCount));--}}
        {{--        });--}}
        {{--        $("form#camperinfo .tab-content").append(empty);--}}
        {{--        $('.nav-tabs a[href="#tab-' + camperCount + '"]').tab('show');--}}
        {{--        bind(empty);--}}
        {{--    });--}}

        {{--    $('input:submit').on('click', function (e) {--}}
        {{--        e.preventDefault();--}}
        {{--        var form = $("#camperinfo");--}}
        {{--        $(this).val("Saving").removeClass("btn-primary btn-danger").prop("disabled", true);--}}
        {{--        $(".has-danger").removeClass("has-danger");--}}
        {{--        $(".is-invalid").removeClass("is-invalid");--}}
        {{--        $(".invalid-feedback").remove();--}}
        {{--        $("div.alert").remove();--}}
        {{--        if (!confirm("You are registering " + form.find('select.days option[value!="0"]:selected').length + " campers for {{ $year->year }}. Is this correct?")) {--}}
        {{--            $(this).val("Resubmit").addClass("btn-danger").prop("disabled", false);--}}
        {{--            return false;--}}
        {{--        }--}}
        {{--        $.ajax({--}}
        {{--            url: form.attr("action"),--}}
        {{--            type: 'post',--}}
        {{--            data: form.serialize(),--}}
        {{--            success: function (data) {--}}
        {{--                $(".nav-tabs").before("<div class='alert alert-success'>" + data + "</div>");--}}
        {{--                $("input:submit").val("Saved").addClass("btn-success").prop("disabled", false);--}}
        {{--                $('html,body').animate({--}}
        {{--                    scrollTop: 0--}}
        {{--                }, 700);--}}
        {{--            },--}}
        {{--            error: function (data) {--}}
        {{--                if (data.status === 500) {--}}
        {{--                    $(".nav-tabs").before("<div class='alert alert-danger'>Unknown error occurred. Please use the Contact Us form to ask for assistance and include the approximate time you received this message.</div>");--}}
        {{--                } else {--}}
        {{--                    var errorCount = data !== undefined ? Object.keys(data.responseJSON.errors).length : '';--}}
        {{--                    $.each(data.responseJSON.errors, function (k, v) {--}}
        {{--                        var group = $("#" + k.replace(".", "-")).parents(".form-group").addClass("has-danger");--}}
        {{--                        group.find("select,input").addClass('is-invalid');--}}
        {{--                        group.find("div:first").append("<span class=\"invalid-feedback\"><strong>" + this[0] + "</strong></span>");--}}
        {{--                    });--}}
        {{--                    $("span.invalid-feedback").show();--}}
        {{--                    $(".nav-tabs").before("<div class='alert alert-danger'>You have " + errorCount + " error(s) in your form. Please adjust your entries and resubmit.</div>");--}}
        {{--                    $('.nav-tabs a[href="#' + $("span.invalid-feedback:first").parents('div.tab-pane').attr('id') + '"]').tab('show');--}}
        {{--                }--}}
        {{--                $("input:submit").val("Resubmit").addClass("btn-danger").prop("disabled", false);--}}
        {{--                $('html,body').animate({--}}
        {{--                    scrollTop: 0--}}
        {{--                }, 700);--}}
        {{--            }--}}
        {{--        });--}}
        {{--    });--}}
        {{--});--}}

        {{--function templateReschurch(data) {--}}
        {{--    if (!data.id) return data.text;--}}
        {{--    return mark(data.name, data.term) + ' (' + mark(data.city, data.term) + ', ' + mark(data.statecode.name, data.term) + ')';--}}
        {{--}--}}

        {{--function templateSelchurch(data) {--}}
        {{--    if (!data.name) return data.text;--}}
        {{--    return data.name + ' (' + data.city + ', ' + data.statecode.name + ')';--}}
        {{--}--}}

        {{--function bind(obj) {--}}
        {{--    obj.find("button#quickme").on("click", function (e) {--}}
        {{--        e.preventDefault();--}}
        {{--        $("select.days").val('6');--}}
        {{--    });--}}
        {{--    obj.find(".nextcamper").off("click").click(nextCamper);--}}
        {{--    obj.find("input[name='firstname[]']").on("change", function () {--}}
        {{--        $('a[href="#' + $(this).parents('div.tab-pane').attr('id') + '"]').text($(this).val());--}}
        {{--    });--}}
        {{--    obj.find(".select-program").on("change", function () {--}}
        {{--        var tab = $(this).parents('div.tab-pane');--}}
        {{--        var days = tab.find("select.days");--}}
        {{--        if (days.val() === "0") {--}}
        {{--            $(this).next(".alert").removeClass("d-none");--}}
        {{--        } else {--}}
        {{--            $(this).next(".alert").addClass("d-none");--}}
        {{--        }--}}
        {{--    });--}}
        {{--    obj.find("select.churchlist").select2({--}}
        {{--        ajax: {--}}
        {{--            url: '/data/churchlist',--}}
        {{--            dataType: 'json',--}}
        {{--            quietMillis: 250,--}}
        {{--            processResults: function (data) {--}}
        {{--                return {--}}
        {{--                    results: data--}}
        {{--                };--}}
        {{--            }--}}
        {{--        },--}}
        {{--        escapeMarkup: function (markup) {--}}
        {{--            return markup;--}}
        {{--        },--}}
        {{--        minimumInputLength: 3,--}}
        {{--        placeholder: 'Church Search',--}}
        {{--        templateResult: templateReschurch,--}}
        {{--        templateSelection: templateSelchurch,--}}
        {{--        theme: 'bootstrap'--}}
        // });
        // }
    </script>
@endsection
