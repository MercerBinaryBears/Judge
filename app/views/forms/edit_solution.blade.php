@extends('layout')

@section('content')

<div class="col-md-6">
	<dl class='dl-horizontal'>
		<dt>Problem</dt>
		<dd>{{ $solution->problem->name }}</dt>
		<dt>Submitted</dt>
		<dd>{{ $solution->created_at }}</dd>
		<dt>Team</dt>
		<dd>{{$solution->user->username}}</dd>
		<dt>Language</dt>
		<dd>{{$solution->language->name}}</dd>
		<dt>Download</dt>
		<dd>{{ link_to_route('solution_package', 'Solution Package', array($solution->id), array('_target'=>'blank'))}}</dd>
	</dl>
</div>

<div class="col-md-6">
	{{ Form::open(array('route'=>array("edit_solution", $solution->id))) }}
	{{ Form::label('solution_state_id', 'Solution State: ') }}
	{{ Form::select('solution_state_id', $solution_states->lists('name','id'))}}
	{{ Form::submit('Save')}}
	{{ Form::close() }}

	{{ Form::open(array('route'=>array("unclaim_solution", $solution->id)))}}
	{{ Form::submit('Unclaim') }}
	{{ Form::close() }}
</div>

@stop
