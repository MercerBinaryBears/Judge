@foreach($submissions as $submission)
	Submission at {{ $submission->created_at }}
	for {{ $submission->problem->name }}
	Status: {{-- TODO: get status here --}}
@endforeach