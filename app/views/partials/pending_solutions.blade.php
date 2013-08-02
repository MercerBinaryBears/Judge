@foreach($submissions as $submission)
	Submission at {{ $submission->created_at }}
	for {{ $submission->problem->name }}
	by {{ $submission->user->name }}
	{{-- Add a judge claim button --}}
@endforeach