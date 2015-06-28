<div class="panel panel-{{ $solution->solution_state->bootstrap_color }} solution-panel">
    <div class="panel-heading">
        <div class="panel-title">
            {{ $solution->problem->name }}
        </div>
    </div>
    <div class="panel-body">
        Submitted <span class="label label-info">{{ $solution->submissionPrettyDiff() }}</span>
        @if(Auth::user()->team && $solution->claiming_judge_id != null)
            and judged
            <span class="label label-{{ $solution->solution_state->bootstrap_color }}">{{ $solution->solution_state->name }}</span>
            by <span class="label label-primary">{{ $solution->claimingJudge->username }}</span>
        @else
            by <span class="label label-primary">{{ $solution->user->username }}</span>
        @endif
    </div>
    @if(Auth::user()->judge || Auth::user()->admin)
    <div class="panel-footer">
        {{ link_to_route('edit_solution', 'Edit', array('id'=>$solution->id), array('class' => 'btn btn-default')) }}
    </div>
    @endif
</div>
