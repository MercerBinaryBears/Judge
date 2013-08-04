@extends('layout')

@section('content')
	<ul class="solutions">
	@foreach($solutions as $solution)
		<li>
			Solution {{$solution->created_at}} for {{$solution->problem->name}} by {{$solution->user->username}} <br/>
			<a href="/judge/solutions/{{$solution->id}}/edit">Claim</a>
		</li>
	@endforeach
	</ul>
@stop