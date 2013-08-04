@extends('layout')

@section('content')
	<div class="submissions">
	@foreach($submissions as $submission)
		Submission at {{ $submission->created_at }}
		for {{ $submission->problem->name }}
		by {{ $submission->user->username }}
		{{-- Add a judge claim button --}}
	@endforeach
	</div>
	<div class="submission_form">
	</div>
@stop