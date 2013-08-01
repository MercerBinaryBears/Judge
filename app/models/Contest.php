<?php
use\LaravelBook\Ardent\Ardent as Ardent;

class Contest extends Ardent {
	public static $rules = array(
		'name' => 'required',
		'start_at' => 'required',
		);
}