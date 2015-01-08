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
<br/>
@if($message->responder)
    <pre>{{ $message->response_text }}</pre>
    <br/>
    From {{ $message->responder->username }}
@else
<div class="panel-group">
    <div class="panel panel-default">
        <div class="panel-heading" data-toggle="collapse" data-target="#respond-form-{{ $message->id}}">
            <h4 class="panel-title">Send Response</h4>
        </div>
        <div id="respond-form-{{ $message->id }}" class="collapse">
            <div class="panel-body">
                @include('forms.respond_to_message')
            </div>
        </div>
    </div>
</div>
@endif

</div>
