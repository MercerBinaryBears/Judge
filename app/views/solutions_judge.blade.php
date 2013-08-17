@extends('layout')

@section('content')
	<div class="span6">
		<h3>Unjudged Solutions</h3>
		<ul class="solutions unstyled">
		@foreach($unjudged_solutions as $solution)
			<li>
				<dl class='dl-horizontal'>
					<dt>Problem</dt>
					<dd>{{ $solution->problem->name }}</dt>
					<dt>Submitted</dt>
					<dd>{{ $solution->created_at }}</dd>
					<dt>Team</dt>
					<dd>{{$solution->user->username}}</dd>
					<dt></dt>
					<dd>{{ link_to_route('edit_solution', 'Claim', array('id'=>$solution->id), array()) }}</dd>
				</dl>
			</li>
		@endforeach
		</ul>
	</div>
	<div class="span6">
		<h3>Your Judged Solutions</h3>
		<ul class="solutions unstyled">
		@foreach($claimed_solutions as $solution)
			<li>
				<dl class='dl-horizontal'>
					<dt>Problem</dt>
					<dd>{{ $solution->problem->name }}</dt>
					<dt>Submitted</dt>
					<dd>{{ $solution->created_at }}</dd>
					<dt>Team</dt>
					<dd>{{ $solution->user->username}}</dd>
					<dt>Judged as</dt>
					<dd>{{ $solution->solution_state->name }}</dd>
					<dt></dt>
					<dd>{{ link_to_route('edit_solution', 'Edit', array('id'=>$solution->id), array()) }}</dd>
				</dl>
			</li>
		@endforeach
		</ul>
	</div>
@stop