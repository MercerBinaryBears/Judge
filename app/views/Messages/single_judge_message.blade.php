<blockquote>
    <h4>
    @if($message->problem)
        For {{ $message->problem->name }}
    @else
        General Question
    @endif
    </h4>
    <p>{{ $message->text }}</p>
    <footer> {{ $message->sender->username }}</footer>
    @if($message->responder)
        <blockquote>
            <p>{{ $message->response_text }}</p>
            <footer>{{ $message->responder->username }}</footer>
        </blockquote>
    @else
        <div class="panel-group">
            <div class="panel panel-default">
                <div class="panel-heading" data-toggle="collapse" data-target="#respond-form-{{ $message->id}}">
                    <h4 class="panel-title">Send Response</h4>
                </div>
                <div id="respond-form-{{ $message->id }}" class="collapse">
                    <div class="panel-body">
                        @include('forms.respond_to_message')
                    </div>
                </div>
            </div>
        </div>
@endif

</blockquote>
<hr/>
