<form method="POST" action="/messages/{{ $message->id }}">
    {{ Form::hidden('responder_id', Auth::user()->id) }}
    {{ Form::label('response_text', 'Response') }}
    {{ Form::textarea('text', null, ['class' => 'form-control']) }}
    <br/>
    <input type="submit" class="btn btn-default" />
</form>
