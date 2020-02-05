<div class="carousel-caption">
    <h3 class="font-weight-bold text-letter-spacing-xs text-uppercase">
        Midwest Unitarian Universalist Summer Assembly
    </h3>
    <h5 class="d-none d-md-block">
        An annual intergenerational Unitarian Universalist retreat for fun, fellowship, and personal growth
    </h5>
    <div class="lead mb-2">
        {{ $year->first_day }} through {{ $year->last_day }} {{ $year->year }}
    </div>
    <div>
        @can('has-paid')
            <a href="{{ route('campers.index') }}" class="btn btn-primary btn-lg">
                See Your Information for {{ $year->year }} <i class="fas fa-sign-in"></i>
            </a>
        @else
            <button type="button" class="btn btn-primary btn-lg" data-toggle="modal"
                    data-target="#modal-register">
                Register for {{ $year->year }} <i class="fas fa-sign-in"></i>
            </button>
        @endif
    </div>
</div>
