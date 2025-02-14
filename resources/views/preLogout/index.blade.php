@extends('app')

@section('content')

<div class="container-fluid">
    <div class="row vertical-center-row">
        <div class="text-center col-md-4 col-md-offset-4">
            <div class="panel panel-default">
				<div class="panel-heading" style="background-color:#f3afaf"> <span style="color:red">Odjava <b>Preproduction</b> operatera</span></div>
				
					{!! Form::open(['method'=>'POST', 'url'=>'/preproduction_logout_post']) !!}

						<div class="panel-body">
		            		<p>Izabrati :</p>
		            		
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


						<input type="radio" class="btn btn-primary" name="pauza[]" value="da" id="da" style='width: 30px; height: 30px;'
						@if (isset($pauza) and $pauza == 'da')
							checked
						@endif
						>
						<label class="btn btn-secondary" for="da" >Prosla je pauza &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
						<br><br>
						<input type="radio" class="btn btn-primary" name="pauza[]" value="ne" id="ne" style='width: 30px; height: 30px;'
						@if (isset($pauza) and $pauza == 'ne')
							checked
						@endif
						>
						<label class="btn btn-secondary" for="ne">Nije prosla pauza &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
						
						
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
		                    <p style="color:red;"><b>Izaberite opciju da li je pauza prosla ili ne!</b></p>
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
		                    <p style="color:green;"><b>Odjava operatera <big><i>{{ $msg4 }}</i></big> uspesno sacuvana.</b></p>
		                 </div>
		                @endif
		</div>
	</div>
</div>

@endsection