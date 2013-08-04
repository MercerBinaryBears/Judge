<?php

return array(
	'title' => 'Problems',

	'single' => 'Problem',

	'model' => 'Problem',

	'columns' => array(
		'name' => array(
			'title' => 'Problem Name'
			),
		'contest' => array(
			'title' => 'Contest',
			'relationship' => 'contest',
			'select' => '(:table).name'
			),

		),
	'edit_fields' => array(
		'name' => array(
			'title' => 'Problem Name',
			'type' => 'text'
			),
		'contest' => array(
			'title' => 'Contest',
			'type' => 'relationship',
			'name_field' => 'name',
			),
		'judging_input' => array(
			'title' => 'Judging Input',
			'type' => 'file',
			'location' => '/tmp',
			),
		'judging_output' => array(
			'title' => 'Judging Output',
			'type' => 'file',
			'location' => '/tmp',
			)
		)
	);