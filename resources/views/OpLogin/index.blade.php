@extends('app')

@section('content')

<div class="container-fluid">
    <div class="row vertical-center-row">
        <div class="text-center col-md-4 col-md-offset-4">
            <div class="panel panel-default">
				<div class="panel-heading" style="background-color: #b3b5ff;"><span style="color:blue">Prijava Cutting operatera</span></div>
				
					{!! Form::open(['method'=>'POST', 'url'=>'/login_operator']) !!}

						<div class="panel-body">
							<p>Izabrati aktivnost:</p>
						<select name="activity" class="form-control" class="autofocus">
		                	<option value="0"></option>
		                    @foreach ($activity as $line)
		                    <option value="{{ $line->activity_desc }}">{{ $line->activity_desc }}</option>
		                    @endforeach
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
		                  @if (isset($msg1))
		                 <div class="panel-body">
		                    <p style="color:red;"><b>Aktivnost mora biti izabrana!</b></p>
		                 </div>
		                 @endif

		                 @if (isset($msg2))
		                 <div class="panel-body">
		                    <p style="color:red;"><b>R broj radnika mora biti unesen ili skeniran!</b></p>
		                 </div>
		                 @endif

		                 @if (isset($msg3))
		                 <div class="panel-body">
		                    <p style="color:red;"><b>Radnik nije pronadjen u Inteos-u !</b></p>
		                 </div>
		                 @endif

		                 @if (isset($msg4))
		                 <div class="panel-body">
		                    <p style="color:green;"><b>Aktivnost uspesno sacuvana.</b></p>
		                 </div>
		                 @endif
				
				
		</div>
	</div>
</div>

@endsection