@extends('layout')

@section('content')
	<ul class="solutions">
	@foreach($solutions as $solution)
		<li>
			Solution at {{ $solution->created_at }}
			for {{ $solution->problem->name }}
			by {{ $solution->user->username }}
			{{-- Add a judge claim button --}}
		</li>
	@endforeach
	</ul>
	<div class="solution_form">
	</div>
@stop