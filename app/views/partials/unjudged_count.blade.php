@if($unjudged_count > 0 && (Auth::user()->admin || Auth::user()->judge))
<span class="badge">{{ $unjudged_count }}</span>
@endif
