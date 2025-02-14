@extends('app')

@section('content')

<div class="container-fluid">
    <div class="row vertical-center-row">
        <div class="text-center col-md-4 col-md-offset-4">
            <div class="panel panel-default">
				<div class="panel-heading" style="background-color:#f3afaf"><span style="color:red">Prijava <b>Preproduction</b> operatera </span></div>
				
					{!! Form::open(['method'=>'POST', 'url'=>'/preproduction_login_post2']) !!}

						{!! Form::hidden('smena', $smena) !!}
						{!! Form::hidden('rnumber', $rnumber) !!}

						<div class="panel-body">
		            		<p>Izabrati :</p>
		            		
		            
						<input type="radio" class="btn btn-primary" name="pauza[]" value="da" id="da" style='width: 30px; height: 30px;'						@if (isset($pauza) and $pauza == 'da')
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
						<br>
						
		            	</div>
		            	<hr>
						
						
						
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

		                @if (isset($msg5) and ($msg5 != ''))
		                 <div class="panel-body">
		                    <p style="color:green;"><b>Prijava operatera u ne standardnom vremenskom razdoblju moraju izabrati da li su bili na pauzi ili ne.</b></p>
		                 </div>
		                @endif
		</div>
	</div>
</div>

@endsection