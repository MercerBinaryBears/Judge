@extends('layout')

@section('content')
<div class="row">
    <div class="col-md-4">
        @include('forms.create_solution')
        <hr/>
    </div>
    <div class="col-md-8">
        @foreach($solutions as $solution)
            <div class="row">
                <div class="col-md-12">
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
                                {{$solution->claimingJudge->username}}
                            @else
                                No one yet
                            @endif
                        </dd>
                    </dl>
                </div>
            </div>
            <hr/>
        @endforeach
    </div>
</div>

@stop
