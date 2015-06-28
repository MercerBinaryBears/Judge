<dl class='dl-horizontal bg-{{ $solution->solution_state->bootstrap_color }}'>
    <dt>Problem</dt>
    <dd>{{ $solution->problem->name }}</dt>
    <dt>Submitted</dt>
    <dd>{{ $solution->submissionPrettyDiff() }}</dd>
    @if(Auth::user()->team)
        <dt>Judged By</dt>
        <dd>
            @if($solution->claiming_judge_id != null)
                {{$solution->claimingJudge->username}}
            @else
                No one yet
            @endif
        </dd>
    @else
        <dt>Team</dt>
        <dd>{{ $solution->user->username}}</dd>
    @endif
    <dt>Judged as</dt>
    <dd>{{ $solution->solution_state->name }}</dd>
    <dt></dt>
    @if(Auth::user()->judge || Auth::user()->admin)
    <dd>{{ link_to_route('edit_solution', 'Edit', array('id'=>$solution->id), array('class' => 'btn btn-default')) }}</dd>
    @endif
    </dl>
<hr/>

