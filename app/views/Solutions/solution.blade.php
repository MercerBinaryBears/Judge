<dl class='dl-horizontal bg-{{ $solution->solution_state->bootstrap_color }}'>
    <dt>Problem</dt>
    <dd>{{ $solution->problem->name }}</dt>
    <dt>Submitted</dt>
    <dd>{{ $solution->submissionPrettyDiff() }}</dd>
    <dt>Team</dt>
    <dd>{{ $solution->user->username}}</dd>
    <dt>Judged as</dt>
    <dd>{{ $solution->solution_state->name }}</dd>
    <dt></dt>
    <dd>{{ link_to_route('edit_solution', 'Edit', array('id'=>$solution->id), array('class' => 'btn btn-default')) }}</dd>
    </dl>
<hr/>

