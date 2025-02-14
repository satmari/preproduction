@extends('app')

@section('content')
<div class="container-fluid">
	<div class="row vertical-center-row">
		<div class="text-center col-md-8 col-md-offset-2">
			<div class="panel panel-default"> 
				<div class="panel-heading" style="background-color: #b3f700;"><span style="color:blue">Tabela Preproduction (KIK) registracija (prikazano poslednjih 30 dana) </span></div>
				
				<br>
                <div class="input-group"> <span class="input-group-addon">Filter</span>
                    <input id="filter" type="text" class="form-control" placeholder="Type here...">
                </div>

                <table class="table table-striped table-bordered" id="sort" 
                data-show-export="true"
                data-export-types="['excel']"
                >
                <!--
                data-show-export="true"
                data-export-types="['excel']"
                data-search="true"
                data-show-refresh="true"
                data-show-toggle="true"
                data-query-params="queryParams" 
                data-pagination="true"
                data-height="300"
                data-show-columns="true" 
                data-export-options='{
                         "fileName": "preparation_app", 
                         "worksheetName": "test1",         
                         "jspdf": {                  
                           "autotable": {
                             "styles": { "rowHeight": 20, "fontSize": 10 },
                             "headerStyles": { "fillColor": 255, "textColor": 0 },
                             "alternateRowStyles": { "fillColor": [60, 69, 79], "textColor": 255 }
                           }
                         }
                       }'
                -->
				    <thead>
				        <tr>
				           {{-- <th>id</th> --}}
				           <th data-sortable="true">Datum prijave</th>
				           <th data-sortable="true">Smena</th>
				           
				           <th data-sortable="true">R Number</th>
				           <th >Operater</th>
				           <th data-sortable="true">Aktivnost</th>	
				           <th>Vreme prijave</th>
				           <th>Vreme odjave</th>
				           <th>Razlog ili nacin odjave</th>

				           <th data-sortable="true">od</th>
				           <th data-sortable="true">do</th>

				           
				        </tr>
				    </thead>
				    <tbody class="searchable">
				    
				    @foreach ($data as $d)
				    	
				        <tr>
				        	{{-- <td>{{ $d->id }}</td> --}}
				        	<td>{{ substr($d->login_date,0,10) }}</td>
				        	<td>{{ $d->shift_name }}</td>
				        	
				        	<td>{{ $d->rnumber }}</td>
				        	<td>{{ $d->operator }}</td>
				        	<td>{{ $d->activity_desc}}</td>

				        	<td>{{ substr($d->login_actual,0,19) }}</td>
				        	<td>{{ substr($d->logout_actual,0,19) }}</td>

				        	<td>{{ $d->logout_motivation}}</td>

				        	<td>{{ substr($d->shift_start,11,5) }}</td>
				        	<td>{{ substr($d->shift_end,  11,5) }}</td>


						</tr>
				    
				    @endforeach
				    </tbody>

				</table>
			</div>
		</div>
	</div>
</div>

@endsection