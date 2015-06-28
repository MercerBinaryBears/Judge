@extends('layout')

@section('content')
	<h4 class="well well-small">
		Your API key is {{ $api_key }}
	</h4>
    <div class="row">
        <div class="col-md-6">
            <h3>Unjudged Solutions</h3>
            @foreach($unjudged_solutions as $solution)
                @include('Solutions.solution', compact('solution'))
            @endforeach
            </ul>
        </div>
        <div class="col-md-6">
            <h3>Your Judged Solutions</h3>
            @foreach($claimed_solutions as $solution)
                @include('Solutions.solution', compact('solution'))
            @endforeach
        </div>
    </div>
@stop
