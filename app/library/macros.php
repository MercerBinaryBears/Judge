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

			// TODO: convert this to an implode for concise-ness and readability
			foreach($error->all('<li>:message</li>') as $html_error) {
				$html .= $html_error;
			}
			$html .= "</ul>";
		}
	}
	$html .= "</div>";
	return $html;
});