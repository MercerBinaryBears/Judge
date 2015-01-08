<blockquote>
    <h4>
    @if($message->problem)
        Re: {{ $message->problem->name }}
    @else
        General Question
    @endif
    </h4>
    <p>{{ $message->text }}</p>

@if($message->responder)
    <blockquote>
        {{ $message->response_text }}
        <footer>{{ $message->responder->username }}</footer>
    </blockquote>
@endif
</blockquote>
<hr/>
