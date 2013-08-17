<!doctype HTML>
<html>
	<head>
		<title>Judge</title>
		@section('scripts')
			<script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/js/bootstrap.min.js"></script>
		@show
		@section('styles')
			<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.min.css" rel="stylesheet">
		@show
	</head>
	<body>
		<div class='login'>
			@include('partials.login')
		</div>
		{{ HTML::flash() }}
		<div id='content'>
			@section('content')
			@show
		</div>
	</body>
</html>
