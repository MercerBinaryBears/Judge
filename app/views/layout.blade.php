<!doctype HTML>
<html>
	<head>
		<title>Judge</title>
		@section('styles')
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
        <style rel="stylesheet" type="text/css">
        body {
            padding-top: 70px;
        }
        </style>
		@show
	</head>
	<body>
		<div class='container'>
			<div class='row'>
				@include('partials.nav')
			</div>
			{{ HTML::flash() }}
			<div id='content' class='row'>
				@section('content')
				@show
			</div>
		</div>
		@section('scripts')
        <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
		@show
	</body>
</html>
