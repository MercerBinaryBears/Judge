{{-- TODO: get URL --}}
{{ Form::open(array('url'=>'', 'files'=>true)) }}
{{ Form::label('problem', 'Submit a solution to') }}
{{-- TODO: get problem list (for current contest) --}}
{{ Form::select('problem', array())}}
{{ Form::file()}}
{{ Form::submit('Submit')}}
{{ Form::close() }}
