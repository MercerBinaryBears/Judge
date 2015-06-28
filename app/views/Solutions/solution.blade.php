<div class="panel panel-{{ $solution->solution_state->bootstrap_color }}">
    <div class="panel-heading">
        <div class="panel-title">
            {{ $solution->problem->name }}
        </div>
    </div>
    <div class="panel-body">
        Submitted {{ $solution->submissionPrettyDiff() }}
        @if(Auth::user()->team && $solution->claiming_judge_id != null)
            and judged {{ $solution->solution_state->name }}
            by {{ $solution->claimingJudge->username }}
        @else
            by {{ $solution->user->username }}
        @endif
    </div>
    @if(Auth::user()->judge || Auth::user()->admin)
    <div class="panel-footer">
        {{ link_to_route('edit_solution', 'Edit', array('id'=>$solution->id), array('class' => 'btn btn-default')) }}
    </div>
    @endif
</div>
