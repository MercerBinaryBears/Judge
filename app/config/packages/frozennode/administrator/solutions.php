<?php

return array(
	'title' => 'Solutions',

	'single' => 'Solution',

	'model' => 'Solution',

	'columns' => array(
		'problem' => array(
			'title' => 'Problem',
			'relationship' => 'problem',
			'select' => '(:table).name',
			),
		'user' => array(
			'title' => 'User',
			'relationship' => 'user',
			'select' => '(:table).username'
			),
		'solution_state' => array(
			'title' => 'Solution State',
			'relationship' => 'solutionState',
			'select' => '(:table).name'
			),
		'claiming_judge' => array(
			'title' => 'Claiming Judge',
			'relationship' => 'claimingJudge',
			'select' => '(:table).username',
			),

		),
	'edit_fields' => array(
		'solutionState' => array(
			'title' => 'Solution State',
			'type' => 'relationship',
			'name_field' => 'name'
			),
		'claimingJudge' => array(
			'title' => 'Claiming Judge',
			'type' => 'relationship',
			'name_field' => 'username',
			)
		)
	);