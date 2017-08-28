@extends('app')

@section('content')

<div class="container-fluid">
    <div class="row vertical-center-row">
        <div class="text-center col-md-4 col-md-offset-4">
            <div class="panel panel-default">
				<div class="panel-heading">Prijava operatera</div>
				<br>
					{!! Form::open(['method'=>'POST', 'url'=>'/login_operator']) !!}

						<div class="panel-body">
						<p>Skenirati barkod sa kartice operatera: </p>
							{!! Form::text('rnumber', null, ['class' => 'form-control', 'autofocus' => 'autofocus']) !!}
						</div>
						
						
						<br>
						
						{!! Form::submit('Confirm', ['class' => 'btn  btn-success center-block']) !!}

						@include('errors.list')

					{!! Form::close() !!}
				
				<hr>
				<div class="panel-body">
					<div class="">
						<a href="{{url('/c3')}}" class="btn btn-default">Back</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection