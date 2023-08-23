@extends('app')

@section('content')

<div class="container-fluid">
    <div class="row vertical-center-row">
        <div class="text-center col-md-4 col-md-offset-4">
            <div class="panel panel-default">
				<div class="panel-heading"  style="background-color:#f3afaf"><b>Da li ste sigurni da zelite obrisati registraciju?</b></div>
					<br>
					{!! Form::open(['url' => 'remove_line_post']) !!}
						{!! Form::hidden('id', $id, ['class' => 'form-control']) !!}
						@include('errors.list')
						{!! Form::submit('Obrisi', ['class' => 'btn btn-danger btn center-block']) !!}

					{!! Form::close() !!}
				<br>
			</div>
		</div>
	</div>
</div>
@endsection
