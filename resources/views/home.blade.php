@extends('app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Wellcome to login operator application</div>

				
			</div>

			<div class="panel panel-default">
				<div class="panel-heading" style="background-color: #b3b5ff !important"><span style="color:blue">Cutting (SU) operator login</div>

				<div class="panel-body">
					
					<li><a href="{{ url('operator_login_new') }}"><span style="color:#31b0d5">Prijava / Log IN</span></a></li>
					<li><a href="{{ url('operator_change_new') }}"><span style="color:#f0ad4e">Promena aktivnosti / Change activity</span></a></li>
					<li><a href="{{ url('operator_logout_new') }}"><span style="color:#c9302c">Odjava / Log OUT</span></a></li>
					<li role="separator" class="divider"></li>
					<li><a href="{{ url('activity') }}"><span style="color:blue">Tabela aktivnosti</span></a></li>
					<li><a href="{{ url('table_operator_new') }}"><span style="color:blue">Tabela aktivnih registracija</span></a></li>
								
				</div>
			</div>
			
			<div class="panel panel-default" >
				<div class="panel-heading" style="background-color: #f3afaf !important"><span style="color:red">Preproduction (SU) operator login</span></div>

				<div class="panel-body">
					<li><a href="{{ url('preproduction_login') }}"><span style="color:red">Prijava / LogIN</span></a></li>
					<li><a href="{{ url('preproduction_logout') }}"><span style="color:red">Odjava / LogOUT</span></a></li>
					<li><a href="{{ url('table_preproduction') }}"><span style="color:red">Tabela registracija</span></a></li>
				</div>
			</div>


			<div class="panel panel-default">
				<div class="panel-heading" style="background-color: #b8ffb3 !important"><span style="color:blue">Preproduction (KIK) operator login</div>

				<div class="panel-body">
					
					<li><a href="{{ url('operator_login_new_kik') }}"><span style="color:#31b0d5">Prijava / Log IN</span></a></li>
					<!-- <li><a href="{{ url('operator_change_new_kik') }}"><span style="color:#f0ad4e">Promena aktivnosti / Change activity</span></a></li> -->
					<li><a href="{{ url('operator_logout_new_kik') }}"><span style="color:#c9302c">Odjava / Log OUT</span></a></li>
					<li role="separator" class="divider"></li>
					<li><a href="{{ url('activity_kik') }}"><span style="color:blue">Tabela aktivnosti</span></a></li>
					<li><a href="{{ url('table_operator_new_kik') }}"><span style="color:blue">Tabela aktivnih registracija</span></a></li>
								
				</div>
			</div>

			

		</div>
	</div>
</div>
@endsection
