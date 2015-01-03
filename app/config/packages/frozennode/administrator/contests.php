<?php

return array(
	'title' => 'Contests',

	'single' => 'Contest',

	'model' => 'Judge\Models\Contest\Contest',

	'columns' => array(
		'name' => array(
			'title' => 'Contest Name'
			),
		'starts_at' => array(
			'title' => 'Starts At'
			),
		'ends_at' => array(
			'title' => 'Ends At'
			),
		),
	'edit_fields' => array(
		'name' => array(
			'title' => 'Contest Name',
			'type' => 'text'
			),
		'users' => array(
			'title' => 'Users in Contest',
			'type' => 'relationship',
			'name_field' => 'username',
			),
		'starts_at' => array(
			'title' => 'Starts At',
			'type' => 'datetime'
			),
		'ends_at' => array(
			'title' => 'Ends At',
			'type' => 'datetime'
			),
		)
	);
