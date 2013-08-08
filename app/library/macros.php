<?php
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