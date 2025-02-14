@extends('app')

@section('content')

<div class="container-fluid">
    <div class="row vertical-center-row">
        <div class="text-center col-md-4 col-md-offset-4">
            <div class="panel panel-default">
				<div class="panel-heading" style="background-color:#f3afaf"><span style="color:red">Prijava <b>Preproduction</b> operatera </span></div>
				
					{!! Form::open(['method'=>'POST', 'url'=>'/preproduction_login_post']) !!}

						<div class="panel-body">
		            		<p>Izabrati smenu:</p>
		            		
		            	<!-- <div>
						  <input type="radio" id="I" name="smena" value="I">
						  <label for="huey">I smena (06-14)</label>
						</div>

						<div>
						  <input type="radio" id="dewey" name="smena" value="II">
						  <label for="dewey">II smena (14-22)</label>
						</div>

						<div>
						  <input type="radio" id="louie" name="smena" value="medj">
						  <label for="louie">Medju smena (07-15)</label>
						</div> -->


						<input type="radio" class="btn btn-primary" name="smena[]" value="I" id="I" style='width: 30px; height: 30px;'
						@if (isset($smena) and $smena == 'I')
							checked
						@endif
						>
						<label class="btn btn-secondary" for="I" >I smena (06h-14h)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
						<br><br>
						<input type="radio" class="btn btn-primary" name="smena[]" value="II" id="II" style='width: 30px; height: 30px;'
						@if (isset($smena) and $smena == 'II')
							checked
						@endif
						>
						<label class="btn btn-secondary" for="II">II smena (14h-22h)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
						<br><br>
						<input type="radio" class="btn btn-primary" name="smena[]" value="M" id="M" style='width: 30px; height: 30px;'
						@if (isset($smena) and $smena == 'M')
							checked
						@endif
						>
						<label class="btn btn-secondary" for="M">Medju smena (07h-15h)&nbsp;&nbsp;</label>
		            	
		            	</div>
		            	<hr>
						<div class="panel-body">
						<p>Skenirati barkod sa kartice operatera (R broj zaposlenog): </p>
							{!! Form::text('rnumber', null, ['class' => 'form-control', 'autofocus' => 'autofocus']) !!}
						</div>
						
						{!! Form::submit('Sacuvaj', ['class' => 'btn  btn-success center-block']) !!}

						@include('errors.list')
						{!! Form::close() !!}

						<hr>
		                @if (isset($msg1) and ($msg1 != ''))
		                 <div class="panel-body">
		                    <p style="color:red;"><b>Smena mora biti izabrana!</b></p>
		                 </div>
		                @endif

		                @if (isset($msg2) and ($msg2 != ''))
		                 <div class="panel-body">
		                    <p style="color:red;"><b>R broj operatera mora biti unesen ili skeniran!</b></p>
		                 </div>
		                @endif

		                @if (isset($msg3) and ($msg3 != ''))
		                 <div class="panel-body">
		                    <p style="color:red;"><b>Operater nije pronadjen u Inteos-u !</b></p>
		                 </div>
		                @endif

		                @if (isset($msg4) and ($msg4 != ''))
		                 <div class="panel-body">
		                    <p style="color:green;"><b>Prijava operatera <big><i>{{ $msg4 }}</i></big> uspesno sacuvana.</b></p>
		                 </div>
		                @endif
		</div>
	</div>
</div>

@endsection