@extends('layout')

@section('content')
	<h4 class="well well-small">
		Your API key is {{ $api_key }}
	</h4>
    <div class="row">
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
                    <dd>{{ link_to_route('edit_solution', 'Claim', array('id'=>$solution->id), array('class' => 'btn btn-default')) }}</dd>
                </dl>
            @endforeach
            </ul>
        </div>
        <div class="col-md-6">
            <h3>Your Judged Solutions</h3>
            @foreach($claimed_solutions as $solution)
                @include('Solutions.solution', compact('solution'))
            @endforeach
        </div>
    </div>
@stop
