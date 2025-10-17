@extends('app')

@section('content')
<div class="container container-table">
    <div class="row vertical-center-row">
        <div class="text-center col-md-6 col-md-offset-3">
            <br>
            <div class="panel panel-default">
                <div class="panel-heading" style="background-color: #aaa"><b>Add employee:</b>
                        
                </div>

                @if (isset($msg))
                    <small><i>&nbsp &nbsp &nbsp Msg: <span style="color:green"><b>{{ $msg }}</b></span></i></small>
                @endif
                @if (isset($msge))
                    <small><i>&nbsp &nbsp &nbsp Msg: <span style="color:red"><b>{{ $msge }}</b></span></i></small>
                @endif
        
                {!! Form::open(['url' => 'new_cutting_department_operator_post']) !!}
                    
                    <br>
                    <!-- {!! Form::text('r_number', null, ['class' => 'form-control','autofocus' => 'autofocus']) !!}</td> -->
                    <p>Employee : </p>
                        <select name="r_number" class="chosen narrow-chosen" data-placeholder="Select employee" data-allow_single_deselect="true">
                            <option value=""></option>
                            @foreach ($operators as $line)
                                <option value="{{ $line->r_number }}-{{ $line->operator }}"
                                >
                                    {{ $line->r_number }} - {{ $line->operator }}
                                </option>
                            @endforeach
                        </select>
                    </p>
                    </table>

                    <hr>
                    <div class="panel-body">
                        <p>Sigurnosna sifra</p>
                        {!! Form::password('sifra', ['class' => 'form-control', 'autofocus' => 'autofocus']) !!}

                    </div>
                    <div class="panel-body">

                        {!! Form::submit('Save', ['class' => 'btn btn-success btn center-block']) !!}
                    </div>
                    
                @include('errors.list')
                {!! Form::close() !!}
                <hr>


                <div class="panel-body">
                    <div class="">
                        <a href="{{url('/cutting_department')}}" class="btn btn-default center-block">Back</a>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>
@endsection
