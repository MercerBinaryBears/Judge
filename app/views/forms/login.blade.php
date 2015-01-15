{{ Form::open(array('url'=>'/login', 'class'=>'navbar-form navbar-left')) }}
    <div class="form-group">
        <div class='input-group'>
            <span class='input-group-addon'>
                <span class='glyphicon glyphicon-user'></span>
            </span>
            {{ Form::text('username', Input::old('username'), array('placeholder'=>'Username', 'class' => 'form-control')) }}
        </div>
        <div class='input-group'>
            <span class='input-group-addon'>
                <span class='glyphicon glyphicon-lock'></span>
            </span>
            {{ Form::password('password', array('placeholder'=>'Password', 'class' => 'form-control')) }}
        </div>
        <span class='input-group'>
            {{ Form::submit('Login', array('class'=>'btn btn-info')) }}
        </span>
    </div>
{{ Form::close() }}
