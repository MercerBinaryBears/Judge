<!doctype HTML>
<html>
	<head>
		<title>Judge</title>
		@section('scripts')
		@show
		@section('styles')
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
