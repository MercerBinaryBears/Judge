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
    <style>
    form {
        display: inline;
    }
    </style>
	{{ Form::open(array('route'=>array("edit_solution", $solution->id), 'class' => 'form-inline')) }}
	{{ Form::label('solution_state_id', 'Solution State: ') }}
	{{ Form::select('solution_state_id', $solution_states->lists('name','id'), '', array('class' => 'form-control'))}}
	{{ Form::submit('Save', array('class' => 'btn btn-info'))}}
	{{ Form::close() }}

	{{ Form::open(array('route'=>array("unclaim_solution", $solution->id), 'class' => 'form-inline'))}}
	{{ Form::submit('Unclaim', array('class' => 'btn btn-warning')) }}
	{{ Form::close() }}
</div>

@stop
