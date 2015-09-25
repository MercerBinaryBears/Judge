<?php

return array(
    'title' => 'Tags',

    'single' => 'Tag',

    'model' => 'Judge\Models\Tag',

    'rules' => Judge\Models\Tag::$rules,

    'columns' => array(
        'name' => array(
            'title' => 'Name'
        ),
        'problems' => array(
            'title' => 'Problems',
            'relationship' => 'problems',
            'select' => 'COUNT((:table).id)'
        ),
    ),

    'edit_fields' => array(
		'name' => array(
			'title' => 'Tag Name',
			'type' => 'text'
        ),
    ),
);
