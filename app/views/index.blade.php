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
		
		@foreach($user_data as $single_user)
			<tr>	
				<td>{{ $single_user['username'] }}</td>
				<td>{{ $single_user['problems_solved'] }}</td>
				<td>{{ $single_user['score'] }}</td>

				@foreach($single_user['problem_info'] as $problem_info)
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
