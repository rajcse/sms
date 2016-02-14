@extends('layouts.app')

@section('content')
<div class="container">
    @include('alerts')
    <div class="row">
        <div class="col-md-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Contacts List
                    <span class="pull-right"><a href="#addContact" data-toggle="modal" data-target="#addContact" class="btn btn-primary btn-xs"><i class="fa fa-plus fa-fw"></i> Add</a></span>
                </div>

                <div class="panel-body">
                    @if (count($contacts) == 0)
                        <p>You have no contacts.</p>
                    @else
                        <div class="list-group">
                            @foreach ($contacts as $contact)
                                <a href="#" class="list-group-item" onclick="$('#number').val('{{ $contact->number }}'); return false;">
                                    {{ $contact->name }}<br />
                                    <span class="text-muted">{{ $contact->number }}</span>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <form role="form" id="form" action="/send" method="post">
                {!! csrf_field() !!}
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Send SMS
                    </div>

                    <div class="panel-body">
                        <div class="form-group">
                            <label for="number">Phone Number</label> <span class="text-muted">You can click on a contact to use their number.</span>
                            <input type="text" name="number" id="number" class="form-control" value="{{ old('number') }}" required />
                        </div>
                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea id="message" maxlength="420" name="message" class="form-control" rows="7">{{ old('message') }}</textarea>
                            <span class="text-muted pull-right" style="padding-top: 0.5em;" id="count_message"></span>
                        </div>
                    </div>
                    <div class="panel-footer text-center">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-send fa-fw"></i> Send SMS</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>
<div id="addContact" class="modal fade" role="dialog">
    <form id="form" action="/save" method="post">
        {!! csrf_field() !!}
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-plus fa-fw"></i> Add Contact</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Contact Name</label>
                        <input type="text" name="name" class="form-control" required />
                    </div>
                    <div class="form-group">
                        <label for="number">Number</label>
                        <input type="text" name="number" maxlength="11" class="form-control" placeholder="09xxxxxxxxx" required />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times fa-fw"></i> Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save fa-fw"></i> Save Contact</button>
                </div>
            </div>

        </div>
    </form>

</div>
<script>
    var text_max = 420;
    $('#count_message').html(text_max + ' characters remaining');

    $('#message').keyup(function() {
        var text_length = $('#message').val().length;
        var text_remaining = text_max - text_length;

        $('#count_message').html(text_remaining + ' characters remaining');
    });
</script>
@endsection
