{{--@component('snippet.navtabs', ['tabs' => $timeslots, 'id'=> 'id', 'option' => 'name'])--}}
{{--@foreach($timeslots as $timeslot)--}}
{{--<div class="tab-pane fade{!! $loop->first ? ' active show' : '' !!}" id="tab-{{ $timeslot->id }}" role="tabpanel">--}}
<ul id="nav-tab{{ $id }}" class="nav nav-tabs" role="tablist">
    @foreach($tabs as $tab)
        <li class="nav-item{{ $loop->first ? ' pl-5' : '' }}">
            <a class="nav-link{{ $loop->first ? ' active' : '' }}" data-toggle="tab" href="#tab-{{ $tab->id }}" role="tab">
                {{ $option == 'fullname' ? $tab->firstname . ' ' . $tab->lastname : $tab->$option }}
            </a>
        </li>
    @endforeach
</ul>
<div id="nav-tab{{ $id }}Content" class="tab-content p-3">
    {{ $slot }}
</div>
