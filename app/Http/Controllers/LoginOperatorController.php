<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use DateTime;
use Carbon\Carbon;

use App\login_operator;
use DB;

class LoginOperatorController extends Controller {

	public function index()
	{
		//
		// dd("test");

		return view('OpLogin.index');
	}

	public function login_operator(Request $request)
	{
		$this->validate($request, ['rnumber'=>'required']);

		$input = $request->all(); 
		//var_dump($input);
	
		$rnumber = $input['rnumber'];
		// dd($rnumber);
		
		$inteos = DB::connection('sqlsrv1')->select(DB::raw("SELECT Name FROM BdkCLZG.dbo.WEA_PersData WHERE BadgeNum = :somevariable
				 "), array(
				'somevariable' => $rnumber,
		));

		// dd($inteos);
		
		if (isset($inteos[0]->Name)) {
			
			try {
				$table = new login_operator;

				$table->rnumber = $rnumber;
				$table->operator = $inteos[0]->Name;
				$table->login_date = date("Y-m-d H:i:s");

				$table->save();
			}
			catch (\Illuminate\Database\QueryException $e) {
				$msg = "Problem to save in table";
				return view('OpLogin.error',compact('msg'));
			}


		} else {
			
			$msg = "Operater not found";
			return view('OpLogin.error',compact('msg'));
		}

		return Redirect::to('/');
		
	}

	public function table_operator() {

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM login_operators ORDER BY created_at desc"));
		return view('OpLogin.table', compact('data'));

	}

}
