<?php

return array(
	'title' => 'Languages',

	'single' => 'Language',

	'model' => 'Judge\Models\Language\Language',

	'columns' => array(
		'name' => array(
			'title' => 'Language Name'
			),
		'extension' => array(
			'title' => 'Extension'
			),
		),
	'edit_fields' => array(
		'name' => array(
			'title' => 'Language Name',
			'type' => 'text',
			),
		'extension' => array(
			'title' => 'Extension',
			'type' => 'text',
			),
		)
	);
