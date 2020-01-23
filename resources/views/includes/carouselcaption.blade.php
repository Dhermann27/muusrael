<div class="carousel-caption">
    <h2 class="font-weight-bold text-letter-spacing-xs text-uppercase">
        Midwest Unitarian Universalist Summer Assembly
    </h2>
    <h4 class="d-none d-md-block">
        An annual intergenerational Unitarian Universalist retreat for fun, fellowship, and personal growth
    </h4>
    <div class="mb-2">
        {{ $year->first_day }} through {{ $year->last_day }} {{ $year->year }}
    </div>
    <div>
        @auth
            <a href="{{ route('campers.index') }}" class="btn btn-primary">
                Register for {{ $year->year }} <i class="fas fa-sign-in"></i>
            </a>
        @else
            <button type="button" class="btn btn-primary" data-toggle="modal"
                    data-target="#modal-register">
                Register for {{ $year->year }} <i class="fas fa-sign-in"></i>
            </button>
        @endif
    </div>
</div>
