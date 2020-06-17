<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use DateTime;
use Carbon\Carbon;

use App\login_operator;
use App\activity;
use DB;

class LoginOperatorController extends Controller {

	public function index()
	{
		//
		// dd("test");
		
		
		$activity = DB::connection('sqlsrv')->select(DB::raw("SELECT activity_desc FROM activity ORDER BY id asc"));
		$activity = (object) $activity;
		// dd($activity);

		$msg1 = '';
		$msg2 = '';
		$msg3 = '';
		$msg4 = '';

		return view('OpLogin.index' , compact('activity'));
	}

	public function login_operator(Request $request)
	{
		// $this->validate($request, ['rnumber'=>'required','activity'=>'required']);

		$msg1 = '';
		$msg2 = '';
		$msg3 = '';
		$msg4 = '';

		$input = $request->all(); 
		// dd($input);
	
		$rnumber = strtoupper($input['rnumber']);
		$activity_desc = $input['activity'];
		// dd($activity);

		if ($activity_desc == "0") {
			$activity = DB::connection('sqlsrv')->select(DB::raw("SELECT activity_desc FROM activity ORDER BY id asc"));
			$activity = (object) $activity;
			// dd($activity);
			$msg1 = 'problem';
			return view('OpLogin.index' , compact('activity', 'msg1'));
		}
		if ($rnumber == "") {
			$activity = DB::connection('sqlsrv')->select(DB::raw("SELECT activity_desc FROM activity ORDER BY id asc"));
			$activity = (object) $activity;
			// dd($activity);
			$msg2 = 'problem';
			return view('OpLogin.index' , compact('activity', 'msg2'));
		}
			
		
		$inteos = DB::connection('sqlsrv1')->select(DB::raw("SELECT Name FROM BdkCLZG.dbo.WEA_PersData WHERE BadgeNum = :somevariable
				 "), array(
				'somevariable' => $rnumber,
		));

		// dd($inteos);
		
		if (isset($inteos[0]->Name)) {

			$activity = DB::connection('sqlsrv')->select(DB::raw("SELECT id FROM activity WHERE activity_desc = '".$activity_desc."' "));
			// dd($activity[0]->id);
			// dd($rnumber);

			// try {
				$table = new login_operator;

				$table->rnumber = $rnumber;
				$table->operator = $inteos[0]->Name;
				$table->login_date = date("Y-m-d H:i:s");

				$table->activity_id = $activity[0]->id;
				$table->activity_desc = $activity_desc;

				$table->save();
			// }
			// catch (\Illuminate\Database\QueryException $e) {
			// 	$msg = "Problem to save in table";
			// 	return view('OpLogin.error',compact('msg'));
			// }


		} else {
			
			$activity = DB::connection('sqlsrv')->select(DB::raw("SELECT activity_desc FROM activity ORDER BY id asc"));
			$activity = (object) $activity;
			// dd($activity);
			$msg3 = 'problem';
			return view('OpLogin.index' , compact('activity', 'msg3'));
		}

		// return Redirect::to('/');

		$activity = DB::connection('sqlsrv')->select(DB::raw("SELECT activity_desc FROM activity ORDER BY id asc"));
		$activity = (object) $activity;
		// dd($activity);
		$msg4 = 'Uspesno';
		return view('OpLogin.index' , compact('activity', 'msg4'));
		
	}

	public function table_operator() {

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM login_operators WHERE  created_at >= DATEADD(day,-30,GETDATE()) ORDER BY created_at desc"));
		return view('OpLogin.table', compact('data'));

	}

}
