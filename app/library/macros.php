<?php
/*
|-------------------------------------------------------------------------
| Custom macros
|-------------------------------------------------------------------------
|
| This file contains custom HTML macros to help with form building, etc.
|
*/

/**
 * Flashes any error messages to the page if there are stored in Session
 */
HTML::macro('flash', function() {
	$html = "<div class\'error\'>";
	if(Session::has('error')) {
		$error = Session::get('error');
		if(is_string($error)) {
			$html .= $error;
		}
		else {
			$html .= "<ul>";
			$html .= implode('', $error->all('<li>:message</li>'));
			$html .= "</ul>";
		}
	}
	$html .= "</div>";
	return $html;
});