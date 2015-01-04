<div>
@if($message->problem)
    For {{ $message->problem->name }}
@else
    General Message
@endif
<br/>
<pre>{{ $message->text }}</pre>
<br/>
From {{ $message->sender->username }}

</div>
