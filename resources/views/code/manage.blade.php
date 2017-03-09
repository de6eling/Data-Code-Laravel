@extends('layouts.app')

@section('content')]
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            @if ($groups)
                @foreach($groups as $key => $group)
                    <h2>{{ $group->name }}</h2>
                    <p>Sharing is caring. Group key: {{ $group->key }}</p>

                    <h4>Files</h4>
                    @if(sizeof($files[$key]) != 0)
                        <ul>
                        @foreach($files[$key] as $groupFiles)
                            <li><a href="/code/{{ $groupFiles->id }}">{{ $groupFiles->name }}</a></li>
                        @endforeach
                        </ul>
                    @else
                        <p>There are no files in this group. Go ahead and add some.</p>
                    @endif

                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#newFileModal">
                        ADD FILE
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="newFileModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="myModalLabel">Modal title</h4>
                                </div>
                                <div class="modal-body">
                                    {!! Form::open(['url' => '/code', 'METHOD' => 'POST', 'files' => 'TRUE']) !!}
                                    <input type="hidden" name="groupId" value="{{ $group->id }}">
                                    <input type="file" name="code">
                                    {{ Form::submit('Upload') }}
                                    {!! Form::close() !!}
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary">Save changes</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
@endsection