@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.min.css"/>
    <style>
        .sortable {
            list-style-type: none;
            margin: 0;
            padding: 0;
            width: 60%;
        }

        .sortable li:hover {
            cursor: pointer;
        }
    </style>
@endsection

@section('title')
    Coffeehouse
@endsection

@section('heading')
    Use this schedule to determine who's onstage tonight
@endsection

@section('content')
    <div class="container">
        <form id="coffeeform" class="form-horizontal" role="form" method="POST" action="{{ url('/coffeehouse') }}">
            @include('snippet.flash')

            <ul class="nav nav-tabs flex-column flex-lg-row" role="tablist">
                <li role="presentation" class="nav-item">
                    <a href="#1" aria-controls="1" role="tab"
                       class="nav-link{{ $day == '1' || $day == null ? ' active' : '' }}"
                       data-toggle="tab">Monday</a>
                </li>
                <li role="presentation" class="nav-item">
                    <a href="#2" aria-controls="2" role="tab" class="nav-link{{ $day == '2' ? ' active' : '' }}"
                       data-toggle="tab">Tuesday</a>
                </li>
                <li role="presentation" class="nav-item">
                    <a href="#3" aria-controls="3" role="tab" class="nav-link{{ $day == '3' ? ' active' : '' }}"
                       data-toggle="tab">Wednesday</a>
                </li>
                <li role="presentation" class="nav-item">
                    <a href="#4" aria-controls="4" role="tab" class="nav-link{{ $day == '4' ? ' active' : '' }}"
                       data-toggle="tab">Thursday (Raunch Night)</a>
                </li>
                <li role="presentation" class="nav-item">
                    <a href="#5" aria-controls="5" role="tab" class="nav-link{{ $day == '5' ? ' active' : '' }}"
                       data-toggle="tab">Friday</a>
                </li>
            </ul>
            <div class="tab-content">
                @while($firstday->dayOfWeek < 6)
                    <div role="tabpanel"
                         class="tab-pane fade{{ $firstday->addDay()->dayOfWeek == $day || ($day == null && $firstday->dayOfWeek == 1) ? ' active show' : '' }}"
                         aria-expanded="{{ $firstday->dayOfWeek == $day || ($day == null && $firstday->dayOfWeek ==  1) ? 'true' : 'false' }}"
                         id="{{ $firstday->dayOfWeek }}">
                        <?php $starttime->hour(20)->minute(50); ?>
                        <h5>{{ $firstday->toDateString() }} </h5>
                        <ul class="list-group sortable col-md-10 col-sm-4">
                            @foreach($acts->where('date', $firstday->toDateString())->all() as $act)
                                <li id="{{ $act->id }}"
                                    class="list-group-item list-group-item-action {{ $act->is_onstage == '1' ? ' list-group-item-primary' : '' }}">
                                    @if($readonly === false)
                                        <div class="float-right">
                                            <label for="{{ $act->id }}-is_onstage" class="sr-only">Delete</label>
                                            <input id="{{ $act->id }}-is_onstage" name="{{ $act->id }}-is_onstage"
                                                   {{ $act->is_onstage == '1' ? 'checked="checked"' : '' }} value="1"
                                                   type="checkbox"/>
                                            On Stage?

                                            <label for="{{ $act->id }}-delete" class="sr-only">Delete</label>
                                            <input id="{{ $act->id }}-delete" name="{{ $act->id }}-delete"
                                                   type="checkbox"/>
                                            Delete?

                                            <i class="far fa-sort fa-2x"></i>
                                        </div>
                                        <input type="hidden" id="{{ $act->id }}-order" name="{{ $act->id }}-order"/>
                                    @endif
                                    {{ $starttime->addMinutes(10)->format('g:iA') }} {{ $act->name }}
                                    @if($readonly === false)
                                        ({{ $act->equipment }})
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                        @include('includes.formgroup', ['type' => 'next', 'label' => '', 'attribs' => ['name' => 'Next Day ']])
                    </div>
                @endwhile
            </div>
            @if($readonly === false)
                <div class="well">
                    <h4>Add New Act</h4>
                    @include('includes.formgroup', ['type' => 'select', 'label' => 'Days', 'attribs' => ['name' => 'day'],
                        'list' => [['id' => '1', 'name' => 'Monday'], ['id' => '2', 'name' => 'Tuesday'],
                        ['id' => '3', 'name' => 'Wednesday'], ['id' => '4', 'name' => 'Thursday'],
                        ['id' => '5', 'name' => 'Friday']], 'option' => 'name'])

                    @include('includes.formgroup', ['label' => 'Act Name',
                        'attribs' => ['name' => 'name', 'placeholder' => 'Brief title']])

                    @include('includes.formgroup', ['label' => 'Equipment',
                        'attribs' => ['name' => 'equipment', 'placeholder' => 'Will appear to A/V crew, not campers']])

                    @include('includes.formgroup', ['label' => 'Display Order',
                        'attribs' => ['name' => 'order', 'data-number-to-fixed' => '0',
                        'placeholder' => 'Position in which to display the act', 'min' => '1']])

                    @include('includes.formgroup', ['type' => 'submit', 'label' => '', 'attribs' => ['name' => 'Save Changes']])
                </div>
            @endif
        </form>
    </div>
@endsection

@section('script')
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.min.js"
            integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
    <script src="/js/jquery.ui.touch-punch.min.js"></script>
    <script type="text/javascript">
        $("ul.sortable").sortable({
            placeholder: "ui-state-highlight"
        }).each(function () {
            $(this).find("li.list-group-item-primary").not(":last").removeClass("list-group-item-primary").addClass("list-group-item-dark");
        });

        $('#coffeeform').on('submit', function (e) {
            $("ul.sortable").each(function () {
                $(this).find("li").each(function (index) {
                    $("#" + $(this).attr("id") + "-order").val(index + 1);
                });
            });
            return true;
        });
    </script>
@endsection
