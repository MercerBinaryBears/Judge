<!doctype HTML>
<html>
	<head>
		<title>Judge</title>
		@section('styles')
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="/theme.css" />
		@show
	</head>
	<body>
		<div class='container-fluid'>
			<div class='row'>
                <div class='col-md-1'>
				@include('partials.nav')
                </div>
                <div class='col-md-11'>
                    <h1 class="text-center">{{ $contest_name }}</h1>
                    {{ HTML::flash() }}
                    @section('content')
                    @show
                    @if(file_exists(base_path('VERSION')))
                    <div class="text-center text-muted">
                        <a href="https://github.com/chipbell4/Judge/tree/{{ file_get_contents(base_path('VERSION')) }}">Version {{ file_get_contents(base_path('VERSION')) }}</a>
                    </div>
                    @endif
                </div>
            </div>
		</div>
		@section('scripts')
        <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
        <script>    
        $(function() {
            $('[data-toggle=tooltip]').tooltip();
        });
        </script>
		@show
	</body>
</html>
