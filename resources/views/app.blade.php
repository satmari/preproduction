<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Operator App</title>

	<link href="{{ asset('/css/app.css') }}" rel="stylesheet">
	<!-- <link href="{{ asset('/css/css.css') }}" rel="stylesheet"> -->
	<!-- <link href="{{ asset('/css/custom.css') }}" rel="stylesheet"> -->

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle Navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="{{ url('/') }}">Operator App</a>
			</div>

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<a class="navbar-brand" href="#">|</a>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class="" style="color:blue"><big><b>&nbsp;&nbsp;&nbsp; Cutting &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></big></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="{{ url('operator_login/') }}"><span style="color:blue">Prijava Operatera</span></a></li>
							<li><a href="{{ url('table_operator') }}"><span style="color:blue">Tabela registracija</span></a></li>
							<li><a href="{{ url('activity') }}"><span style="color:blue">Tabela aktivnosti</span></a></li>
							
						</ul>
					</li>
					<a class="navbar-brand" href="#">|</a>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class="" style="color:red"><big><b>Preproduction &nbsp;&nbsp;&nbsp;</b></big></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="{{ url('preproduction_login') }}"><span style="color:red">Prijava / LogIN</span></a></li>
							<li><a href="{{ url('preproduction_logout') }}"><span style="color:red">Odjava / LogOUT</span></a></li>
							<li><a href="{{ url('table_preproduction') }}"><span style="color:red">Tabela registracija</span></a></li>
						</ul>
					</li>
					<a class="navbar-brand" href="#">|</a>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class="" style="color:yellow"><big><b>NEW LOGIN (TEST)&nbsp;&nbsp;&nbsp;</b></big></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="{{ url('operator_login_new') }}"><span style="color:yellow">Prijava / Log IN</span></a></li>
							<li><a href="{{ url('operator_change_new') }}"><span style="color:yellow">Promena aktivnosti / Change activity</span></a></li>
							<li><a href="{{ url('operator_logout_new') }}"><span style="color:yellow">Odjava / Log OUT</span></a></li>

							<li><a href="{{ url('table_operator_new') }}"><span style="color:yellow">Tabela aktivnih registracija</span></a></li>
						</ul>
					</li>
					
					
					
				</ul>

				{{-- 
				<ul class="nav navbar-nav navbar-right">
					@if (Auth::guest())
						<li><a href="{{ url('/auth/login') }}">Login</a></li>
						<li><a href="{{ url('/auth/register') }}">Register</a></li>
					@else
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="{{ url('/auth/logout') }}">Logout</a></li>
							</ul>
						</li>
					@endif
				</ul>
				--}}
			</div>
		</div>
	</nav>

	@yield('content')

	<!-- Scripts -->
	<script src="{{ asset('/js/jquery.min.js') }}" type="text/javascript" ></script>
    <script src="{{ asset('/js/bootstrap.min.js') }}" type="text/javascript" ></script>
    <script src="{{ asset('/js/bootstrap-table.js') }}" type="text/javascript" ></script>
	<script src="{{ asset('/js/jquery-ui.min.js') }}" type="text/javascript" ></script>
	<!-- <script src="{{ asset('/js/jquery.dataTables.min.js') }}" type="text/javascript" ></script>-->
	<!--<script src="{{ asset('/js/jquery.tablesorter.min.js') }}" type="text/javascript" ></script>-->
	<!--<script src="{{ asset('/js/custom.js') }}" type="text/javascript" ></script>-->
	<script src="{{ asset('/js/tableExport.js') }}" type="text/javascript" ></script>
	<!--<script src="{{ asset('/js/jspdf.plugin.autotable.js') }}" type="text/javascript" ></script>-->
	<!--<script src="{{ asset('/js/jspdf.min.js') }}" type="text/javascript" ></script>-->
	<script src="{{ asset('/js/FileSaver.min.js') }}" type="text/javascript" ></script>
	<script src="{{ asset('/js/bootstrap-table-export.js') }}" type="text/javascript" ></script>

	<script type="text/javascript">
$(function() {
    	
	// $('#po').autocomplete({
	// 	minLength: 3,
	// 	autoFocus: true,
	// 	source: '{{ URL('getpodata')}}'
	// });
	// $('#module').autocomplete({
	// 	minLength: 1,
	// 	autoFocus: true,
	// 	source: '{{ URL('getmoduledata')}}'
	// });

	$('#filter').keyup(function () {

        var rex = new RegExp($(this).val(), 'i');
        $('.searchable tr').hide();
        $('.searchable tr').filter(function () {
            return rex.test($(this).text());
        }).show();
	});


	$('#myTabs a').click(function (e) {
  		e.preventDefault()
  		$(this).tab('show')
	});
	$('#myTabs a:first').tab('show') // Select first tab

	$(function() {
    	$( "#datepicker" ).datepicker();
  	});

  	
	$('#sort').bootstrapTable({
    	
	});

	//$('.table tr').each(function(){
  		
  		//$("td:contains('pending')").addClass('pending');
  		//$("td:contains('confirmed')").addClass('confirmed');
  		//$("td:contains('back')").addClass('back');
  		//$("td:contains('error')").addClass('error');
  		//$("td:contains('TEZENIS')").addClass('tezenis');

  		// $("td:contains('TEZENIS')").function() {
  		// 	$(this).index().addClass('tezenis');
  		// }
	//});

	$('.days').each(function(){
		var qty = $(this).html();
		//console.log(qty);

		if (qty < 7 ) {
			$(this).addClass('zeleno');
		} else if ((qty >= 7) && (qty <= 15)) {
			$(this).addClass('zuto');
		} else if (qty > 15 ) {	
			$(this).addClass('crveno');
		}
	});


	// $('.status').each(function(){
	// 	var status = $(this).html();
	// 	//console.log(qty);

	// 	if (status == 'pending' ) {
	// 		$(this).addClass('pending');
	// 	} else if (status == 'confirmed') {
	// 		$(this).addClass('confirmed');
	// 	} else {	
	// 		$(this).addClass('back');
	// 	}
	// });

	// $('td').click(function() {
	//    	var myCol = $(this).index();
 	//    	var $tr = $(this).closest('tr');
 	//    	var myRow = $tr.index();

 	//    	console.log("col: "+myCol+" tr: "+$tr+" row:"+ myRow);
	// });

});
</script>

</body>
</html>
