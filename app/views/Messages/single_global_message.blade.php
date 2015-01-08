<blockquote>
    <h4>
    @if($message->problem)
        {{ $message->problem->name }}
    @else
        General Message
    @endif
    </h4>
    <p>{{ $message->text }}</p>
    <footer>{{ $message->sender->username }}</footer>
</blockquote>
<hr/>
