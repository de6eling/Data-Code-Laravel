@extends('layouts.app')

@section('content')


<div class="container-fluid">
    <div class="row">
        <ul class="nav nav-tabs">

            @foreach($sheets as $sheet)
                @if($loop->index == 0)
                    <li role="presentation" class="active"><a href="#{{ $loop->index }}" data-toggle="tab" onclick="sheetSwitch({{$loop->index}})">{{$sheet->name}}</a></li>
                @else
                    <li role="presentation"><a href="#{{ $loop->index }}" data-toggle="tab" onclick="sheetSwitch({{$loop->index}})">{{$sheet->name}}</a></li>
                @endif
            @endforeach
        </ul>
        <div class="tab-content">
            @foreach($sheets as $sheet)
                @if($loop->index == 0)
                    <div class="tab-pane active" id="{{ $loop->index }}">
                        <div class="col-sm-8">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Code</h3>
                                </div>
                                <div class="panel-body">
                                    {{-- MODAL--}}
                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#displayModal0">
                                        Display Settings
                                    </button>


                                    <div class="modal fade" id="displayModal0" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">Modal title</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <p>What variables would you like to diplay on this sheet?</p>
                                                    <ul>
                                                        @foreach($variables[$loop->index] as $variable)
                                                            <li><input type="checkbox" onclick="displayVar({{ $variable->id }})"> {{ $variable->name }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                    <button type="button" class="btn btn-primary">Save changes</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- END MODAL --}}

                                    <div id="content0"></div>
                                    <div>
                                        <h3>Codes</h3>
                                        <div id="codes0"></div>
                                    </div>
                                    <button class="btn btn-primary btn-sm disabled" onclick="move(0)">BACK</button>
                                    <button class="btn btn-primary btn-sm" onclick="move(1)">NEXT</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Codebook</h3>
                                </div>
                                <div class="panel-body">
                                    code book stuff goes here.
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="tab-pane" id="{{ $loop->index }}">
                        <div class="col-sm-8">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Code</h3>
                                </div>
                                <div class="panel-body">
                                    {{-- MODAL--}}
                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#displayModal{{ $loop->index }}">
                                        Display Settings
                                    </button>


                                    <div class="modal fade" id="displayModal{{ $loop->index }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">Modal title</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <p>What variables would you like to diplay on this sheet?</p>
                                                    <ul>
                                                        @foreach($variables[$loop->index] as $variable)
                                                            <li><input type="checkbox" onclick="displayVar({{ $variable->id }})"> {{ $variable->name }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                    <button type="button" class="btn btn-primary">Save changes</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- END MODAL --}}
                                    <div id="content{{ $loop->index }}">
                                    </div>
                                    <h3>Codes</h3>
                                    <div id="codeList{{ $loop->index }}"></div>
                                    <button class="btn btn-primary btn-sm" onclick="move(1)">NEXT</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Codebook</h3>
                                </div>
                                <div class="panel-body">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>add</th>
                                                <th>name</th>
                                                <th>description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($codes[$loop->index] as $code)
                                            <tr>
                                            <th><button class="btn btn-success btn-sm" onclick="addCodeToPoint({{ $code->id }}, '{{ $code->name }}')" id="code{{ $code->id }}">+</button></th>
                                            <th>{{ $code->name }}</th>
                                            <th>{{ $code->description }}</th>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    {{-- Modal --}}
                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#newCode{{ $loop->index }}">
                                        Add Code
                                    </button>
                                    <div class="modal fade" id="newCode{{ $loop->index }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">Add New Code to Book</h4>
                                                </div>
                                                <div class="modal-body">
                                                    {!! Form::open(['url' => '/api/codebook', 'METHOD' => 'POST', 'id' => 'newCode'.$loop->index]) !!}
                                                    <input type="text" name="codeName" placeholder="New code name">
                                                    <input type="text" name="codeDescription" placeholder="New code description">
                                                    <input type="hidden" name="codeSheet" value="{{ $sheet->id }}">
                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                    {!! Form::close() !!}
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                    <button type="button" onclick="submitNewCode({{ $loop->index }})" class="btn btn-primary">Save changes</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- End modal --}}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>

<script>
    let data = <?php echo json_encode($data); ?>;
    let view = <?php echo json_encode($variables); ?>;
    let pointCodes = <?php echo json_encode($pointCodes); ?>;
    let codes = <?php echo json_encode($codes); ?>;
    let authId = <?php echo Auth::id(); ?>;
    let sheetIndex = 0;
    let pointPlaces = [];

    window.onload = function(){
        console.log(codes);

        // Shuffle the data onload each time and create sheet index
        data.forEach( function (sheet) {
            // Initializes the position of each sheet to 0
            pointPlaces.push(0);

            sheet.forEach(function (obj, index) {
                sheet[index] = Object.values(obj);
            });

        });
        loadSheet();
    };

    function submitNewCode(id) {
        $('#newCode' + id).submit();
    }
    


    function addCodeToPoint(codeId, codeName) {

//        $('#code' + codeId).removeClass('btn-success').addClass('btn-danger').text('-').attr('onclick', 'removeCodeFromPoint(' + codeId + ',"' + codeName + '")');
//        let newHtml = `<button class="btn btn-success" id="pointCode` + codeId + `" onclick="removeCodeFromPoint(` + codeId + `,'` + codeName + `')">` + codeName + `  x  ` + `</button>`;
//        $('#codeList' + sheetIndex).append(newHtml);

        pointCodes.push({
            'codeId': codeId,
            'datapointId': data[sheetIndex][pointPlaces[sheetIndex]][0].id,
            'name': codeName,
            'userId' : authId

        });

        loadCodes();

        // Working with ajax
        // Resource: https://tutorials.kode-blog.com/laravel-5-ajax-tutorial
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        let fData = {
            'codeId': codeId,
            'pointId': data[sheetIndex][pointPlaces[sheetIndex]][0].id
        };

        console.log($('meta[name="csrf-token"]').attr('content'));

        $.ajax({

            type: 'POST',
            url: '/api/codebook/add',
            data: fData,
            dataType: 'json',
            success: function (data) {
                console.log(data);
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
        // Finish ajax
    }

    function removeCodeFromPoint(codeId, codeName) {
        $("#pointCode" + codeId).remove();
        $('#code' + codeId).removeClass('btn-danger').addClass('btn-success').text('+').attr('onclick', 'addCodeToPoint(' + codeId + ',"' + codeName + '")');

        // remove code from object
        pointCodes = pointCodes.filter(function (el) {
           return el.datapointId != data[sheetIndex][pointPlaces[sheetIndex]][0].id;
        });

        // Working with ajax
        // Resource: https://tutorials.kode-blog.com/laravel-5-ajax-tutorial
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({

            type: 'DELETE',
            url: '/api/codebook/remove/' + codeId + '/' + data[sheetIndex][pointPlaces[sheetIndex]][0].id,
            success: function (data) {
                console.log('Success: ', data);
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
        // Finish ajax
    }

    function loadSheet() {
        // Loads the response and data
        let sheetData = data[sheetIndex];
        let itemHtml = "";
        let dataIndex = pointPlaces[sheetIndex];

        codes[sheetIndex].forEach(function (el) {
            $('#code' + el.id).removeClass('btn-danger').addClass('btn-success').text('+').attr('onclick', 'addCodeToPoint(' + el.id + ',"' + el.name + '")');
        });

        view[sheetIndex].forEach(function (title, index) {
            itemHtml += ('<h3>' + title.name + '</h3>' + '<p>' + sheetData[dataIndex][index].value);
        });
        $('#content' + sheetIndex).html(itemHtml);

        loadCodes();
    }

    function loadCodes() {
        let sheetData = data[sheetIndex];
        // Loads the pointCodes associated with that data
        // Eventually should deliver pointCodes by sheet, maybe using the sheet ID with each code?
        console.log(pointCodes);

        let points = pointCodes.filter(function (pointCodes) {
            return pointCodes.datapointId == sheetData[pointPlaces[sheetIndex]][0].id;
        });

        $('#codeList' + sheetIndex).empty();

        points.forEach(function (code) {
            $('#codeList' + sheetIndex).append(
                `<button class="btn btn-success" id="pointCode` + code.codeId + `" onclick="removeCodeFromPoint(` + code.codeId + `,'` + code.name + `')">` + code.name + `  x  ` + `</button>`
            );
            $('#code' + code.codeId).removeClass('btn-success').addClass('btn-danger').text('-').attr('onclick', 'removeCodeFromPoint(' + code.codeId + ',"' + code.name + '")');
        });
    }

    function move(direction) {

        if (pointPlaces[sheetIndex] < data[sheetIndex].length) {
            console.log(pointPlaces[sheetIndex]);
            if(direction) {
                pointPlaces[sheetIndex] = (pointPlaces[sheetIndex] + 1);
            } else {
                pointPlaces[sheetIndex] = (pointPlaces[sheetIndex] - 1);
            }
            loadSheet();
        } else {
            console.log('this doesnt work: ' + pointPlaces[sheetIndex]);
        }
    }

    function displayVar(id) {
        console.log(id);
    }

    function sheetSwitch(sheetId) {
        sheetIndex = sheetId;
        loadSheet();
        console.log(sheetIndex);
    }
</script>


@endsection
