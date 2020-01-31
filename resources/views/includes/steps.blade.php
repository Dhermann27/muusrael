@can('has-paid')
    <ul id="littlesteps" class="nav nav-pills nav-fill mb-4">
        <li class="nav-item mx-5">
            <i id="household-success" class="far fa-check btn-success float-right d-none"></i>
            <a href="{{ route('household.index')}}"
               {{--           . (preg_match('/\/(c|f)\/\d+$/', $_SERVER['REQUEST_URI'], $matches) ? substr($_SERVER['REQUEST_URI'], -7) : '')}}"--}}
               class="nav-link active"
               data-toggle="tooltip" data-placement="bottom" data-container="ul#littlesteps"
               title="Billing Information">
                <i class="far fa-home"></i>
            </a>
        </li>
        <li class="nav-item mx-5">
            <i id="camper-success" class="far fa-check btn-success float-right d-none"></i>
            <a href="{{ route('campers.index') }}"
               {{--           . (preg_match('/\/(c|f)\/\d+$/', $_SERVER['REQUEST_URI'], $matches) ? substr($_SERVER['REQUEST_URI'], -7) : '') }}"--}}
               class="nav-link @if(preg_match('/\/campers/', url()->current(), $matches)) active @endif"
               data-toggle="tooltip" data-placement="bottom" data-container="ul#littlesteps" title="Camper Listing">
                <i class="far fa-users"></i>
            </a>
        </li>
        <li class="nav-item mx-5">
            <i id="payment-success" class="far fa-check btn-success float-right d-none"></i>
            <a href="{{ url('payment') }}"
               {{--           . (preg_match('/\/(c|f)\/\d+$/', $_SERVER['REQUEST_URI'], $matches) ? substr($_SERVER['REQUEST_URI'], -7) : '') }}"--}}
               class="nav-link @if(preg_match('/\/payment/', url()->current(), $matches)) active @endif"
               data-toggle="tooltip" data-placement="bottom" data-container="ul#littlesteps" title="Statement">
                <i class="far fa-usd-square"></i>
            </a>
        </li>
        @if($year->is_live)
            <li class="nav-item mx-5">
                <i id="workshop-success" class="far fa-check btn-success float-right d-none"></i>
                <a href="{{ url('/workshopchoice') . (preg_match('/\/(c|f)\/\d+$/', $_SERVER['REQUEST_URI'], $matches) ? substr($_SERVER['REQUEST_URI'], -7) : '') }}"
                   class="nav-link @if(preg_match('/\/workshopchoice/', url()->current(), $matches)) active @endif"
                   data-toggle="tooltip" data-placement="bottom" data-container="ul#littlesteps"
                   title="Workshop Preferences">
                    <i class="far fa-rocket"></i>
                </a>
            </li>
            <li class="nav-item mx-5">
                <i id="room-success" class="far fa-check btn-success float-right d-none"></i>
                <a href="{{ url('/roomselection') . (preg_match('/\/(c|f)\/\d+$/', $_SERVER['REQUEST_URI'], $matches) ? substr($_SERVER['REQUEST_URI'], -7) : '') }}"
                   class="nav-link @if(preg_match('/\/roomselection/', url()->current(), $matches)) active @endif"
                   data-toggle="tooltip" data-placement="bottom" data-container="ul#littlesteps" title="Room Selection">
                    <i class="far fa-bed"></i>
                </a>
            </li>
            <li class="nav-item mx-5">
                <i id="nametag-success" class="far fa-check btn-success float-right d-none"></i>
                <a href="{{ url('/nametag') . (preg_match('/\/(c|f)\/\d+$/', $_SERVER['REQUEST_URI'], $matches) ? substr($_SERVER['REQUEST_URI'], -7) : '') }}"
                   class="nav-link @if(preg_match('/\/nametag/', url()->current(), $matches)) active @endif"
                   data-toggle="tooltip" data-placement="bottom" data-container="ul#littlesteps"
                   title="Nametag Customization">
                    <i class="far fa-id-card"></i>
                </a>
            </li>
            <li class="nav-item mx-5">
                <i id="medical-success" class="far fa-check btn-success float-right d-none"></i>
                <a href="{{ url('/confirm') . (preg_match('/\/(c|f)\/\d+$/', $_SERVER['REQUEST_URI'], $matches) ? substr($_SERVER['REQUEST_URI'], -7) : '') }}"
                   class="nav-link @if(preg_match('/\/confirm/', url()->current(), $matches)) active @endif"
                   data-toggle="tooltip" data-placement="bottom" data-container="ul#littlesteps"
                   title="Medical Responses">
                    <i class="far fa-envelope"></i>
                </a>
            </li>
        @else
            <li class="nav-item mx-5">
                <a href="#" class="nav-link lighter" data-toggle="tooltip" data-placement="bottom"
                   data-container="ul#littlesteps" title="Workshop Preference opens {{ $year->brochure_date }}">
                    <i class="far fa-rocket"></i>
                </a>
            </li>
            <li class="nav-item mx-5">
                <a href="#" class="nav-link lighter" data-toggle="tooltip" data-placement="bottom"
                   data-container="ul#littlesteps" title="Room Selection opens {{ $year->brochure_date }}">
                    <i class="far fa-bed"></i>
                </a>
            </li>
            <li class="nav-item mx-5">
                <a href="#" class="nav-link lighter" data-toggle="tooltip" data-placement="bottom"
                   data-container="ul#littlesteps" title="Nametags Customization opens {{ $year->brochure_date }}">
                    <i class="far fa-id-card"></i>
                </a>
            </li>
            <li class="nav-item mx-5">
                <a href="#" class="nav-link lighter" data-toggle="tooltip" data-placement="bottom"
                   data-container="ul#littlesteps" title="Medical Responses open {{ $year->brochure_date }}">
                    <i class="far fa-envelope"></i>
                </a>
            </li>
        @endif
    </ul>
@endif
