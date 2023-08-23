@extends('app')

@section('content')
<div class="container-fluid">
	<div class="row vertical-center-row">
		<div class="text-center col-md-8 col-md-offset-2">
			<div class="panel panel-default"> 
				<div class="panel-heading" style="background-color: #b3b5ff;"><span style="color:blue">Tabela Cutting registracija (prikazano poslednjih 30 dana) </span></div>
				
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
				           <th data-sortable="true">R Number</th>
				           <th >Operater</th>
				           <th data-sortable="true">Aktivnost</th>	
				           <th>Created at</th>
				           
				        </tr>
				    </thead>
				    <tbody class="searchable">
				    
				    @foreach ($data as $d)
				    	
				        <tr>
				        	{{-- <td>{{ $d->id }}</td> --}}
				        	<td>{{ Carbon\Carbon::parse($d->login_date)->format('d.m.Y H:i:s') }}</td>
				        	<td>{{ $d->rnumber }}</td>
				        	<td>{{ $d->operator }}</td>
				        	<td>{{ $d->activity_desc}}</td>
				        	<td>{{ $d->created_at}}</td>

						</tr>
				    
				    @endforeach
				    </tbody>

				</table>
			</div>
		</div>
	</div>
</div>

@endsection