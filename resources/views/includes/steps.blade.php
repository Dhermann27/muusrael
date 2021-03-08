@if(session()->has('camper') && Gate::allows('is-council'))
    <h4 class="text-md-center">{{ session()->get('camper')->firstname }} {{ session()->get('camper')->lastname }}
        @if(isset(session()->get('camper')->email))
            &lt;{{ session()->get('camper')->email }}&gt;
            <a href="mailto:{{ session()->get('camper')->email }}"><i class="fa fa-envelope"></i></a>
        @endif
    </h4>
    <input type="hidden" id="camper-id" value="{{ session()->get('camper')->id }}"/>
@endif
@if(Gate::allows('has-paid') || (session()->has('camper') && Gate::allows('is-council')))
    <ul id="littlesteps" class="nav nav-pills nav-fill mb-4">
        <li class="nav-item mx-5">
            <i id="household-success" class="far fa-check btn-success float-right d-none"></i>
            <a href="{{ route('household.index', ['id' => session()->has('camper') ? session()->get('camper')->id : null])}}"
               class="nav-link @if(preg_match('/\/household/', url()->current(), $matches)) active @endif"
               data-toggle="tooltip" data-placement="bottom" data-container="ul#littlesteps"
               title="Billing Information">
                <i class="far fa-home"></i>
            </a>
        </li>
        <li class="nav-item mx-5">
            <i id="camper-success" class="far fa-check btn-success float-right d-none"></i>
            <a href="{{ route('campers.index', ['id' => session()->has('camper') ? session()->get('camper')->id : null]) }}"
               class="nav-link @if(preg_match('/\/campers/', url()->current(), $matches)) active @endif"
               data-toggle="tooltip" data-placement="bottom" data-container="ul#littlesteps" title="Camper Listing">
                <i class="far fa-users"></i>
            </a>
        </li>
            <li class="nav-item mx-5">
                <i id="workshop-success" class="far fa-check btn-success float-right d-none"></i>
                <a href="{{ route('workshopchoice.index', ['id' => session()->has('camper') ? session()->get('camper')->id : null]) }}"
                   class="nav-link @if(preg_match('/\/workshopchoice/', url()->current(), $matches)) active @endif"
                   data-toggle="tooltip" data-placement="bottom" data-container="ul#littlesteps"
                   title="Workshop Preferences">
                    <i class="far fa-rocket"></i>
                </a>
            </li>
    </ul>
@endif
