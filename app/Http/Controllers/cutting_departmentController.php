<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use DateTime;
use Carbon\Carbon;

use App\cutting_department;

use DB;

class cutting_departmentController extends Controller {

	public function index() {

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM cutting_department ORDER BY id asc"));
		return view('cutting_department.index' , compact('data'));

	}

	public function new_cutting_department_operator (Request $request) {
		
		// $data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM cutting_department ORDER BY id asc"));

		$operators = DB::connection('sqlsrv1')->select(DB::raw("SELECT [BadgeNum] as r_number,
				[Name] as operator,[FlgAct] as r_status
		  FROM [BdkCLZG].[dbo].[WEA_PersData]
		  WHERE [BadgeNum] like 'R%' AND [FlgAct] = '1'
		  ORDER BY BadgeNum asc "));

		return view('cutting_department.new_operator' , compact('operators'));
	}

	public function new_cutting_department_operator_post(Request $request) {
		
		$input = $request->all(); 
		// dd($input);
		$input_employee = trim($input['r_number']);
		$sifra = trim($input['sifra']);

		if ($sifra != 'cutop') {
			dd('Pogresna sifra!');
		}
		// dd($input_employee);
		$parts = explode("-", $input_employee);

		// Make sure we have both number and name
		if (count($parts) >= 2) {
		    $r_number = trim($parts[0]);
		    $r_name   = trim($parts[1]);
		} else {
		    dd('Nije izabrana radnica!');
		}
		// dd($r_name);

		$data_check = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM cutting_department 
			WHERE rnumber = '".$r_number."' "));

		if (isset($data_check[0]->rnumber)) {
			dd('Employee already in the table');
		}

		$table = new cutting_department;
		$table->rnumber = $r_number;
		$table->operator = $r_name;
		$table->department = 'Cutting department';
		$table->save();

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM cutting_department ORDER BY id asc"));
		$msg = 'Succesfuly saved';
		return view('cutting_department.index' , compact('data','msg'));

	}

	public function cutting_department_operator_edit($id) {

		$data = DB::connection('sqlsrv')->select("SELECT * FROM cutting_department 
			WHERE id = '".$id."' ");

		$operators = DB::connection('sqlsrv1')->select(DB::raw("SELECT [BadgeNum] as r_number,
			[Name] as operator,[FlgAct] as r_status
		  FROM [BdkCLZG].[dbo].[WEA_PersData]
		  WHERE [BadgeNum] like 'R%' and [FlgAct] = '1'
		  ORDER BY BadgeNum asc "));
		// dd($operators);

	    return view('cutting_department.edit_operator', compact('data','operators','id'));
	}

	public function edit_cutting_department_operator_post (Request $request) {
		
		$input = $request->all(); 
		// dd($input);
		$id = trim($input['id']);
		$input_employee = trim($input['r_number']);
		$sifra = trim($input['sifra']);

		if ($sifra != 'cutop') {
			dd('Pogresna sifra!');
		}

		// dd($input_employee);
		$parts = explode("-", $input_employee);

		// Make sure we have both number and name
		if (count($parts) >= 2) {
		    $r_number = trim($parts[0]);
		    $r_name   = trim($parts[1]);
		} else {
		    dd('Nije izabrana radnica!');
		}
		// dd($r_name);

		$data_check = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM cutting_department 
			WHERE rnumber = '".$r_number."' "));

		if (isset($data_check[0]->rnumber)) {
			dd('Employee already in the table');
		}

		$table = cutting_department::findOrFail($id);
		$table->rnumber = $r_number;
		$table->operator = $r_name;
		$table->department = 'Cutting department';
		$table->save();

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM cutting_department ORDER BY id asc"));
		$msg = 'Succesfuly saved';
		return view('cutting_department.index' , compact('data','msg'));

	}

	public function cutting_department_operator_delete ($id) {

		// dd($id);
		return view('cutting_department.delete' , compact('id'));
	}

	public function cutting_department_operator_delete_id (Request $request) {
		
		$input = $request->all(); 
		// dd($input);
		$id = trim($input['id']);
		$sifra = trim($input['sifra']);

		if ($sifra != 'cutop') {
			dd('Pogresna sifra!');
		}

		// dd($id);
		$record = cutting_department::findOrFail($id);
		$record->delete();

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM cutting_department ORDER BY id asc"));
		$msg = 'Succesfuly removed';
		return view('cutting_department.index' , compact('data','msg'));

	}
}
