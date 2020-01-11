@if($year->is_live)
    @if($workshop->capacity == 999)
        <span class="alert alert-success badge float-right">Unlimited Enrollment</span>
    @elseif($workshop->enrolled >= $workshop->capacity)
        <span class="alert alert-danger badge float-right">Waitlist Available</span>
    @elseif($workshop->enrolled >= ($workshop->capacity * .75))
        <span class="alert alert-warning badge float-right">Filling Fast!</span>
    @else
        <span class="alert alert-info badge float-right">Open For Enrollment</span>
    @endif
@endif
