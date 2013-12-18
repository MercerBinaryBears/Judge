@extends('layout')

@section('content')
	<table class="table">
		<tr>
			<th>Team Name</th>
			<th>Problems Solved</th>
			<th>Score</th>

			@foreach($problems as $problem)
				<th>{{ $problem->name }}</th>
			@endforeach
		</tr>
		
		@foreach($contest_summaries as $contest_summary)
			<tr>	
				<td>{{ $contest_summary->user->username }}</td>
				<td>{{ $contest_summary->problems_solved}}</td>
				<td>{{ $contest_summary->penalty_points }}</td>

				@foreach($contest_summary->problem_summaries as $problem_info)
					<td>{{ $problem_info['points_for_problem'] }} / {{ $problem_info['num_submissions'] }}</td>
				@endforeach
			</tr>
		@endforeach

	</table>

	<script>

	// auto reload scoreboard every minute
	function reloadPage() {
		window.location.reload(true);
	}
	setTimeout(reloadPage, 60000);

	</script>

@stop
