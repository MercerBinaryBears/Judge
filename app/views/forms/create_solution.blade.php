{{ Form::open(array('route'=>'store_solution', 'files'=>true)) }}
{{ Form::label('problem_id', 'Submit a solution to') }}
{{ Form::select('problem_id', $problems)}}
<br/>
{{ Form::label('language_id', 'Language') }}
{{ Form::select('language_id', $languages) }}
<br/>
{{ Form::file('solution_code') }}
{{ Form::submit('Submit')}}
{{ Form::close() }}
