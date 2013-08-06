@extends('layout')

@section('content')
<ul class="solutions">
	@foreach($solutions as $solution)
	<li>
		Solution submitted at {{ $solution->created_at }}
		for {{ $solution->problem->name }} <br/>
		State: {{ $solution->solution_state->name}}
		@if($solution->claiming_judge_id != null)
			Judged by {{$solution->claiming_judge->username}}
		@endif
	</li>
	@endforeach
</ul>
@stop