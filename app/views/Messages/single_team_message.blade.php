<div>
@if($message->problem)
    For {{ $message->problem->name }}
@else
    General Question
@endif
<br/>
<pre>{{ $message->text }}</pre>
<br/>
From {{ $message->sender->username }}

@if($message->responder)
    <br/>
    <pre>{{ $message->response_text }}</pre>
    <br/>
    From {{ $message->responder->username }}
@endif

</div>
