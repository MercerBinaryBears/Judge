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
			'relationship' => 'solution_state',
			'select' => '(:table).name'
			)

		),
	'edit_fields' => array(
		'solution_state' => array(
			'title' => 'Solution State',
			'type' => 'relationship',
			'name_field' => 'name'
			)
		)
	);