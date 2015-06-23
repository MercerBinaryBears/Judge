<!doctype HTML>
<html>
	<head>
		<title>Judge</title>
		@section('styles')
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
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
            @if(file_exists(base_path('VERSION')))
            <div class="row">
                <div class="col-md-12 text-center text-muted">
                    <a href="https://github.com/chipbell4/Judge/tree/{{ file_get_contents(base_path('VERSION')) }}">Version {{ file_get_contents(base_path('VERSION')) }}</a>
                </div>
            </div>
            @endif
		</div>
		@section('scripts')
        <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
		@show
	</body>
</html>
