<div class="panel panel-{{ $solution->solution_state->bootstrap_color }}">
    <div class="panel-heading">
        <div class="panel-title">
            {{ $solution->problem->name }}
        </div>
        {{ $solution->submissionPrettyDiff() }}
    </div>
    <div class="panel-body">
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
    </div>
    @if(Auth::user()->judge || Auth::user()->admin)
    <div class="panel-footer">
        {{ link_to_route('edit_solution', 'Edit', array('id'=>$solution->id), array('class' => 'btn btn-default')) }}
    </div>
    @endif
</div>
