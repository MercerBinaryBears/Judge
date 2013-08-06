@extends('layout')

@section('content')
<ul class="solutions">
	@foreach($solutions as $solution)
	<li>
		Solution submitted at {{ $solution->created_at }}
		for {{ $solution->problem->name }} <br/>
		State: {{ $solution->solutionState->name}}
		@if($solution->claiming_judge_id != null)
			Judged by {{$solution->claimingJudge->username}}
		@endif
	</li>
	@endforeach
</ul>
@stop