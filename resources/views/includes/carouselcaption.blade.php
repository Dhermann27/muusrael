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
        <a href="{{ url('/registration') }}" id="register-button" class="btn btn-primary font-weight-bold">
            Register for {{ $year->year }}
        </a>
    </div>
</div>