<?php

return array(
	'title' => 'Contests',

	'single' => 'Contest',

	'model' => 'Contest',

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
		'starts_at' => array(
			'title' => 'Starts At',
			'type' => 'datetime'
			),
		'ends_at' => array(
			'type' => 'datetime'
			),
		)
	);