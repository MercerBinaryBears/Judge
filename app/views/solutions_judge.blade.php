@extends('layout')

@section('content')
	<ul class="solutions">
	@foreach($solutions as $solution)
		<li>
			Solution {{$solution->created_at}} for {{$solution->problem->name}} by {{$solution->user->username}} <br/>
			{{ link_to_route('edit_solution', 'Claim', array('id'=>$solution->id), array()) }}
		</li>
	@endforeach
	</ul>
@stop