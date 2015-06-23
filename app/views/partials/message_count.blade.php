@if($message_count > 0 && (Auth::user()->admin || Auth::user()->judge))
<span class="message-count">{{ $message_count }}</span>
@endif
