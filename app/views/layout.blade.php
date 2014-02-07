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
		<div class='container'>
			<div class='row'>
				<div class='logo span1'>
					<img src='logo.png' alt='Judge Logo'/>
				</div>
				<div class='span11'>
					<h1>
						@section('contest_name')
						@show
					</h1>
				</div>
			</div>
			<div class='row'>
				@include('partials.nav')
			</div>
			{{ HTML::flash() }}
			<div id='content' class='row'>
				@section('content')
				@show
			</div>
		</div>
	</body>
</html>
