@extends('app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Wellcome to login operator application</div>

				
			</div>

			<div class="panel panel-default">
				<div class="panel-heading" style="background-color: #b3b5ff !important"><span style="color:blue">Cutting operator login</div>

				<div class="panel-body">
					<li><a href="{{ url('operator_login/') }}"><span style="color:blue">Prijava Operatera</span></a></li>
					<li><a href="{{ url('table_operator') }}"><span style="color:blue">Tabela registracija</span></a></li>
					<li><a href="{{ url('activity') }}"><span style="color:blue">Tabela aktivnosti</span></a></li>		
								
				</div>
			</div>
			
			<div class="panel panel-default" >
				<div class="panel-heading" style="background-color: #f3afaf !important"><span style="color:red">Preproduction operator login</span></div>

				<div class="panel-body">
					<li><a href="{{ url('preproduction_login') }}"><span style="color:red">Prijava / LogIN</span></a></li>
					<li><a href="{{ url('preproduction_logout') }}"><span style="color:red">Odjava / LogOUT</span></a></li>
					<li><a href="{{ url('table_preproduction') }}"><span style="color:red">Tabela registracija</span></a></li>
				</div>
			</div>

			

		</div>
	</div>
</div>
@endsection
