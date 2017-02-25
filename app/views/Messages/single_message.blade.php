<blockquote>
    <h4>
    @if($message->problem)
        Re: {{ $message->problem->name }}
    @else
        General Question
    @endif
    </h4>

    <h5>
    @if(!empty($show_sender))
        {{ $message->sender->username }}
    @endif
    </h5>

    <p>{{ $message->text }}</p>

@if($message->responder)
    <blockquote>
        {{ $message->response_text }}
        <footer>{{ $message->responder->username }}</footer>
    </blockquote>
@endif

@if(!empty($allow_response))
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
