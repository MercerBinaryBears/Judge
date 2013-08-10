@extends('layout')

@section('content')
	<h3>Unjudged Solutions</h3>
	<ul class="solutions">
	@foreach($unjudged_solutions as $solution)
		<li>
			Solution {{$solution->created_at}} for {{$solution->problem->name}} by {{$solution->user->username}} <br/>
			{{ link_to_route('edit_solution', 'Claim', array('id'=>$solution->id), array()) }}
		</li>
	@endforeach
	</ul>
	<h3>Your Judged Solutions</h3>
	<ul class="solutions">
	@foreach($claimed_solutions as $solution)
		<li>
			Solution {{$solution->created_at}} for {{$solution->problem->name}} by {{$solution->user->username}} <br/>
			You judged as {{ $solution->solution_state->name }} <br/>
			{{ link_to_route('edit_solution', 'Claim', array('id'=>$solution->id), array()) }}
		</li>
	@endforeach
	</ul>
@stop