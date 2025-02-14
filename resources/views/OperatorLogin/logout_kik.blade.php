@extends('app')

@section('content')

<div class="container-fluid">
    <div class="row vertical-center-row">
        <div class="text-center col-md-4 col-md-offset-4">
            <div class="panel panel-default">
				<div class="panel-heading" style="background-color: #2cc95c;"><span style="color:black">Odjava Preproduction (KIK) operatera</span>
					<p><br>
						<a href="{{ url('operator_login_new_kik') }}" class="btn btn-info btn-xs ">Prijava</a>
						<!-- <a href="{{ url('operator_change_new_kik') }}" class="btn btn-warning btn-xs ">Promena</a> -->
						<a href="{{ url('operator_logout_new_kik') }}" class="btn btn-danger btn-xs ">Odjava</a>
					</p></div>
				
					{!! Form::open(['method'=>'POST', 'url'=>'/operator_logout_new_post_kik']) !!}

						<div class="panel-body">
							<p>Izabrati razlog odjave:</p>
						<select name="logout_motivation" class="form-control" class="autofocus">
		                	<option value="0"></option>
		                   	<option value="Leave from work">Odlazak sa posla</option>
		                   	<option value="Move to WH">Premestanje u drugi departman - magacin</option>
		                   	<option value="Move to PROD">Premestanje u drugi departman - proizvodnja</option>
		                   	
		                </select>
		            	</div>

						<div class="panel-body">
						<p>Skenirati barkod sa kartice operatera (R broj zaposlenog): </p>
							{!! Form::text('rnumber', null, ['class' => 'form-control']) !!}
						</div>
						<br>
						
						{!! Form::submit('Snimi', ['class' => 'btn  btn-success center-block']) !!}

						@include('errors.list')

					{!! Form::close() !!}
						
						<hr>

		                

		                @if (isset($msg3))
		                <div class="panel-body">
                    		<p style="color:red;"><b>{{ $msg3 }}</b></p>
                 		</div>
		                @endif

		                @if (isset($msg4))
		                <div class="panel-body">
		                   <p style="color:green;"><b>{{ $msg4 }}</b></p>
		                </div>
		                @endif
				
		</div>
	</div>
</div>

@endsection