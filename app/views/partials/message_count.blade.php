@if($message_count > 0 && (Auth::user()->admin || Auth::user()->judge))
<span class="badge">{{ $message_count }}</span>
@endif
