@extends('app')

@section('content')

<div class="container-fluid">
    <div class="row vertical-center-row">
        <div class="text-center col-md-4 col-md-offset-4">
            <div class="panel panel-default">
				<div class="panel-heading" style="background-color:#f3afaf"><span style="color:red"><b>Promena registracije</b></span></div>
				
					{!! Form::open(['method'=>'POST', 'url'=>'/edit_line_post']) !!}
	            		
	            		{!! Form::hidden('id', $id) !!}

		            	<div class="panel-body">
						<p>Unesi lozinku? </p>
							{!! Form::password('lozinka', null, ['class' => 'form-control', 'autofocus' => 'autofocus']) !!}
						</div>
						
						{!! Form::submit('Dalje', ['class' => 'btn  btn-success center-block']) !!}

						@include('errors.list')
						{!! Form::close() !!}

						 @if (isset($msg) and ($msg != ''))
		                 <div class="panel-body">
		                    <p style="color:red;"> {{ $msg }} </p>
		                 </div>
		                @endif
			</div>
		</div>
	</div>
</div>

@endsection