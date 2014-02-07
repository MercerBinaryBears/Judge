@extends('layout')

@section('content')
<div class="span3">
	@include('forms.create_solution')
</div>
<div class="span9">
	<ul class="solutions unstyled">
		@foreach($solutions as $solution)
		<li>
			<dl class='dl-horizontal'>
				<dt>Problem</dt>
				<dd>{{ $solution->problem->name }}</dt>
				<dt>Submitted</dt>
				<dd>{{ $solution->submissionPrettyDiff() }}</dd>
				<dt>State</dt>
				<dd>{{ $solution->solutionState->name}}</dd>
				<dt>Judged By</dt>
				<dd>
					@if($solution->claiming_judge_id != null)
						Judged by {{$solution->claimingJudge->username}}
					@endif
				</dd>
			</dl>
		</li>
		@endforeach
	</ul>
</div>

@stop
