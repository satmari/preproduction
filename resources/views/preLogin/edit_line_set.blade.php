@extends('app')

@section('content')

<div class="container-fluid">
    <div class="row vertical-center-row">
        <div class="text-center col-md-4 col-md-offset-4">
            <div class="panel panel-default">
				<div class="panel-heading"  style="background-color:#f3afaf"><b>Promena registracije</b></div>
					<br>
					{!! Form::open(['url' => 'remove_line']) !!}
						{!! Form::hidden('id', $id, ['class' => 'form-control']) !!}
						@include('errors.list')
						{!! Form::submit('Obrisi registraciju', ['class' => 'btn btn-danger btn center-block']) !!}

					{!! Form::close() !!}

					<hr>
					{!! Form::open(['url' => 'edit_line_set']) !!}
						{!! Form::hidden('id', $id, ['class' => 'form-control']) !!}

						<div class="panel-body">
							<p><b>Downtime/Odsutnost </b> [min]:</p>
							<p><i>Napises ukupan broj MINUTA koliko je operater bio odsutan iz Preprodukcije</i></p>
							<br>
		               		{!! Form::input('number', 'downtime', $downtime, ['class' => 'form-control']) !!}
						</div>

						<div class="panel-body">
							{!! Form::submit('Save', ['class' => 'btn btn-success btn center-block']) !!}
						</div>

						@include('errors.list')

					{!! Form::close() !!}


				<br>
			</div>
		</div>
	</div>
</div>
@endsection
