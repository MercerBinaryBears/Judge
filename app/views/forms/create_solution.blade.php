{{ Form::open(array('route'=>'store_solution', 'files'=>true)) }}
    {{ Form::label('problem_id', 'Submit a solution to') }}
    {{ Form::select('problem_id', $problems->lists('name','id'), '', array('class' => 'form-control'))}}
    <br/>
    {{ Form::label('language_id', 'Language') }}
    {{ Form::select('language_id', $languages->lists('name','id'), '', array('class' => 'form-control')) }}
    <br/>
    {{ Form::file('solution_code', array('class' => 'form-control')) }}
    <br/>
    {{ Form::submit('Submit', array('class' => 'btn btn-info'))}}
{{ Form::close() }}
