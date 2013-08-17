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
				<div class='logo span12'>
					<img src='asdfa' alt='img'/>
					<h1>Logo Here</h1>
				</div>
			<div class='row'>
				<div class='login span12'>
					@include('partials.login')
				</div>
			</div>
			</div>
			{{ HTML::flash() }}
			<div id='content' class='row'>
				@section('content')
				@show
			</div>
		</div>
	</body>
</html>
