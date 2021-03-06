{{-- This should be RESTful--}}
<form method="POST" action="/messages">
    {{ Form::label('problem_id', 'Problem') }}
    {{ Form::select('problem_id',  ['' => 'General'] + $problems->lists('name', 'id'), '', ['class' => 'form-control']) }}
    <br/>
    {{ Form::label('responder_id', 'Team') }}
    {{ Form::select('responder_id', ['' => 'Everyone'] + $teams->lists('username', 'id'), '', ['class' => 'form-control']) }}
    <br/>
    {{ Form::label('text', 'Message') }}
    {{ Form::textarea('text', null, ['class' => 'form-control']) }} 
    <br/>
    <input type="submit" class="btn btn-default" />
</form>
