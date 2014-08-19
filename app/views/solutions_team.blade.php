@extends('layout')

@section('content')
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        @include('forms.create_solution')
    </div>
</div>
<hr/>
<div class="row">
    @foreach($solutions as $solution)
        <div class="col-md-4">
            <dl class='dl-horizontal'>
                <dt>Problem</dt>
                <dd>{{ $solution->problem->name }}</dt>
                <dt>Submitted</dt>
                <dd>{{ $solution->submissionPrettyDiff() }}</dd>
                <dt>State</dt>
                <dd>{{ $solution->solutionState->name}}</dd>
                <dt>Judged By</dt>
                <dd>
                    @if($solution->claiming_judge_id != null)
                        Judged by {{$solution->claimingJudge->username}}
                    @endif
                </dd>
            </dl>
        </div>
    @endforeach
</div>

@stop
