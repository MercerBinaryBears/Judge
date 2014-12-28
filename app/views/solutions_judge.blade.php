@extends('layout')

@section('content')
	<h4 class="well well-small">
		Your API key is {{ $api_key }}
	</h4>
	<div class="col-md-6">
		<h3>Unjudged Solutions</h3>
		@foreach($unjudged_solutions as $solution)
            <dl class='dl-horizontal'>
                <dt>Problem</dt>
                <dd>{{ $solution->problem->name }}</dt>
                <dt>Submitted</dt>
                <dd>{{ $solution->submissionPrettyDiff() }}</dd>
                <dt>Team</dt>
                <dd>{{$solution->user->username}}</dd>
                <dt></dt>
                <dd>{{ link_to_route('edit_solution', 'Claim', array('id'=>$solution->id), array()) }}</dd>
            </dl>
		@endforeach
		</ul>
	</div>
	<div class="col-md-6">
		<h3>Your Judged Solutions</h3>
		@foreach($claimed_solutions as $solution)
            <dl class='dl-horizontal bg-{{ $solution->solution_state->bootstrap_color }}'>
                <dt>Problem</dt>
                <dd>{{ $solution->problem->name }}</dt>
                <dt>Submitted</dt>
                <dd>{{ $solution->submissionPrettyDiff() }}</dd>
                <dt>Team</dt>
                <dd>{{ $solution->user->username}}</dd>
                <dt>Judged as</dt>
                <dd>{{ $solution->solution_state->name }}</dd>
                <dt></dt>
                <dd>{{ link_to_route('edit_solution', 'Edit', array('id'=>$solution->id), array('class' => 'btn btn-default')) }}</dd>
            </dl>
            <hr/>
		@endforeach
	</div>
@stop
