<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use DateTime;
use Carbon\Carbon;

use App\operatorLogin;
use App\operatorLogin_kik;
use App\activity;
use App\activity_kik;
use DB;

class OperatorLoginController extends Controller {

// Table
	public function table_operator_new() {
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT op.*
				,a.activity_desc
				,a.activity_desc1

			FROM [preproduction].[dbo].[operator_logins] as op
			JOIN [preproduction].[dbo].[activity] as a ON a.id = op.activity_id

			WHERE  op.created_at >= DATEADD(day,-30,GETDATE()) 
			ORDER BY op.created_at desc
		"));

		return view('OperatorLogin.table', compact('data'));
	}

// Login
	public function login()	{
		//

		$activity = DB::connection('sqlsrv')->select(DB::raw("SELECT id, activity_desc FROM activity ORDER BY id asc"));
		$activity = (object) $activity;
		// dd($activity);


		$msg3 = '';
		$msg4 = '';

		return view('OperatorLogin.login' , compact('activity'));
	}

	public function operator_login_new_post(Request $request) {
		// $this->validate($request, ['rnumber'=>'required','activity'=>'required']);

		$msg3 = '';
		$msg4 = '';

		
		$input = $request->all(); 
		// dd($input);
		
		$rnumber = strtoupper($input['rnumber']);
		$activity_id = $input['activity'];
		// dd($activity_id);

		if ($activity_id == "0") {
			$activity = DB::connection('sqlsrv')->select(DB::raw("SELECT id, activity_desc FROM activity ORDER BY id asc"));
			$activity = (object) $activity;
			// dd($activity);
			$msg3 = 'Aktivnost mora biti izabrana!';
			return view('OperatorLogin.login' , compact('activity', 'msg3'));
		}

		if ($rnumber == "") {
			$activity = DB::connection('sqlsrv')->select(DB::raw("SELECT id, activity_desc FROM activity ORDER BY id asc"));
			$activity = (object) $activity;
			// dd($activity);
			$msg3 = 'R broj radnika mora biti unesen ili skeniran!';
			return view('OperatorLogin.login' , compact('activity', 'msg3'));
		}
		
		// Find operaot by Rnumber in Inteos
		$inteos = DB::connection('sqlsrv1')->select(DB::raw("SELECT Name FROM WEA_PersData 
			WHERE BadgeNum = :somevariable"), array(
				'somevariable' => $rnumber,
		));

		// dd($inteos);
		// dd($activity_desc);
		
		if (isset($inteos[0]->Name)) {

			$rnumber;
			$activity_id;
			$operator = $inteos[0]->Name;
			$logout_motivation;

			$login_actual = date("Y-m-d H:i:s");
			// dd($login_actual);

			$login_actual_time = date("H:i:s");
			// dd($login_actual_time);

			$currentTime = new DateTime();
			$currentHourMinute = $currentTime->format('H:i');

			// Determine the login date
			if ($currentHourMinute >= '00:00' && $currentHourMinute < '05:45') {
			    // Between 00:00 and 06:10, use the previous day as login date
			    $login_date = $currentTime->modify('-1 day')->format('Y-m-d');
			    $shift_name = '3';
			} else {
			    // Otherwise, use the current day as login date
			    $login_date = $currentTime->format('Y-m-d');
			}

			// Reset $currentTime to the original time (since modify changes the object)
			$currentTime = new DateTime();

			// Determine shift_start based on the specified logic
			if ($currentHourMinute >= '05:45' && $currentHourMinute <= '06:00') {
			    
			    $shift_start = $login_date . ' 06:00';
			    $shift_name = '1';
			} elseif ($currentHourMinute >= '06:00' && $currentHourMinute <= '13:45') {
			    
			    $shift_start = $currentTime->format('Y-m-d H:i');
			    $shift_name = '1';
			} elseif ($currentHourMinute >= '13:45' && $currentHourMinute <= '14:00') {
			    
			    $shift_start = $currentTime->format('Y-m-d') . ' 14:00';
			    $shift_name = '2';
			} elseif ($currentHourMinute >= '14:00' && $currentHourMinute <= '21:45') {
			    
			    $shift_start = $currentTime->format('Y-m-d H:i');
			    $shift_name = '2';
			} elseif ($currentHourMinute >= '21:45' && $currentHourMinute <= '22:00') {
			    
			    $shift_start = $currentTime->format('Y-m-d') . ' 22:00';
			    $shift_name = '3';
			} else {
			    
			    $shift_start = $currentTime->format('Y-m-d H:i');
			    $shift_name = '3';
			}

			// dd($shift_name);
			// Output the results
			// var_dump("Login date: " . $login_date . "<br>");
			// var_dump("Shift start: " . $shift_start . "<br>");

			
			$alredy_logged_today = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM operator_logins WHERE rnumber = '".$rnumber."' and login_date = '".$login_date."' "));

			if (isset($alredy_logged_today[0]->id)) {
				//user already looged today
				
				// previous activity was not closed
				$alredy_logged_today_not_closed_activity = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM operator_logins WHERE rnumber = '".$rnumber."' and login_date = '".$login_date."' and logout_actual is null "));

				if (isset($alredy_logged_today_not_closed_activity[0]->id)){
					// user already have logged today and previous acivity is not closed
					// dd("user already have logged today and previous acivity was not finished, please use function change activity");

					$activity = DB::connection('sqlsrv')->select(DB::raw("SELECT id, activity_desc FROM activity ORDER BY id asc"));
					$activity = (object) $activity;
					// dd($activity);
					$msg3 = 'User already have logged today and previous acivity was not finished, please use function change activity';
					return view('OperatorLogin.login' , compact('activity', 'msg3'));
				}

			}


			$if_logged_from_before = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM operator_logins WHERE rnumber = '".$rnumber."' and logout_actual is null "));
			if (isset($if_logged_from_before[0]->login_date)) {
				// there is some problem because user was not loged out from before
				dd('there is some problem because user was not loged out from '.substr($if_logged_from_before[0]->login_date,0,10).' ,call IT.');

				$activity = DB::connection('sqlsrv')->select(DB::raw("SELECT id, activity_desc FROM activity ORDER BY id asc"));
				$activity = (object) $activity;
				// dd($activity);
				$msg3 = 'there is some problem because user was not loged out from '.substr($if_logged_from_before[0]->login_date,0,10).' ,call IT.';
				return view('OperatorLogin.login' , compact('activity', 'msg3'));
			}


			// dd('$shift_name');

			$table = new operatorLogin;
			$table->key_string = $login_date.'-'.$activity_id.'-'.$rnumber;
			$table->rnumber = $rnumber;
			$table->operator = $operator;
			$table->activity_id = $activity_id;
			$table->login_date = $login_date;
			$table->login_actual = $currentTime;
			$table->shift_start = $shift_start;
			$table->shift_name = $shift_name;
			$table->logout_actual;
			$table->shift_end;
			$table->logout_motivation;
			$table->save();

		} else {
			
			
			$activity = DB::connection('sqlsrv')->select(DB::raw("SELECT id, activity_desc FROM activity ORDER BY id asc"));
			$activity = (object) $activity;
			// dd($activity);
			$msg3 = 'Radnik nije pronadjen u Inteos-u !';
			return view('OperatorLogin.login' , compact('activity', 'msg3'));
					
		}
		

		// return Redirect::to('/');
		$activity = DB::connection('sqlsrv')->select(DB::raw("SELECT id, activity_desc FROM activity ORDER BY id asc"));
		$activity = (object) $activity;
		// dd($activity);
		$msg4 = 'Operater '.$operator.' uspesno prijavljen';
		return view('OperatorLogin.login' , compact('activity', 'msg4'));
	}

// Change 
	public function change() {
		//

		$activity = DB::connection('sqlsrv')->select(DB::raw("SELECT id, activity_desc FROM activity ORDER BY id asc"));
		$activity = (object) $activity;
		// dd($activity);


		$msg3 = '';
		$msg4 = '';

		return view('OperatorLogin.change' , compact('activity'));
	}

	public function operator_change_new_post(Request $request) {


		$msg3 = '';
		$msg4 = '';

		
		$input = $request->all(); 
		// dd($input);
		
		$rnumber = strtoupper($input['rnumber']);
		$activity_id = $input['activity'];
		// dd($activity_id);

		if ($activity_id == "0") {
			$activity = DB::connection('sqlsrv')->select(DB::raw("SELECT id, activity_desc FROM activity ORDER BY id asc"));
			$activity = (object) $activity;
			// dd($activity);
			$msg3 = 'Aktivnost mora biti izabrana!';
			return view('OperatorLogin.change' , compact('activity', 'msg3'));
		}

		if ($rnumber == "") {
			$activity = DB::connection('sqlsrv')->select(DB::raw("SELECT id, activity_desc FROM activity ORDER BY id asc"));
			$activity = (object) $activity;
			// dd($activity);
			$msg3 = 'R broj radnika mora biti unesen ili skeniran!';
			return view('OperatorLogin.change' , compact('activity', 'msg3'));
		}
		
		// Find operaot by Rnumber in Inteos
		$inteos = DB::connection('sqlsrv1')->select(DB::raw("SELECT Name FROM WEA_PersData 
			WHERE BadgeNum = :somevariable"), array(
				'somevariable' => $rnumber,
		));

		// dd($inteos);
		// dd($activity_desc);

		if (isset($inteos[0]->Name)) {

			
			// dd('set');
			// $activity = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM activity WHERE id = '".$activity_id."' "));
			// dd($activity);
			// dd($activity[0]->id);
			// dd($rnumber);

			$rnumber;
			$activity_id;
			$operator = $inteos[0]->Name;
			$logout_motivation;

			$login_actual = date("Y-m-d H:i:s");
			// dd($login_actual);

			$login_actual_time = date("H:i:s");
			// dd($login_actual_time);

			$currentTime = new DateTime();
			$currentHourMinute = $currentTime->format('H:i');

			// Determine the login date
			if ($currentHourMinute >= '00:00' && $currentHourMinute < '05:45') {
			    // Between 00:00 and 06:10, use the previous day as login date
			    $login_date = $currentTime->modify('-1 day')->format('Y-m-d');
			    $shift_name = '3';
			} else {
			    // Otherwise, use the current day as login date
			    $login_date = $currentTime->format('Y-m-d');
			}

			// Reset $currentTime to the original time (since modify changes the object)
			$currentTime = new DateTime();


			// Determine shift_start based on the specified logic
			if ($currentHourMinute >= '05:45' && $currentHourMinute <= '06:00') {
			    
			    $shift_start = $login_date . ' 06:00';
			    $shift_name = '1';
			} elseif ($currentHourMinute >= '06:00' && $currentHourMinute <= '13:45') {
			    
			    $shift_start = $currentTime->format('Y-m-d H:i');
			    $shift_name = '1';
			} elseif ($currentHourMinute >= '13:45' && $currentHourMinute <= '14:00') {
			    
			    $shift_start = $currentTime->format('Y-m-d') . ' 14:00';
			    $shift_name = '2';
			} elseif ($currentHourMinute >= '14:00' && $currentHourMinute <= '21:45') {
			    
			    $shift_start = $currentTime->format('Y-m-d H:i');
			    $shift_name = '2';
			} elseif ($currentHourMinute >= '21:45' && $currentHourMinute <= '22:00') {
			    
			    $shift_start = $currentTime->format('Y-m-d') . ' 22:00';
			    $shift_name = '3';
			} else {
			    
			    $shift_start = $currentTime->format('Y-m-d H:i');
			    $shift_name = '3';
			}

			// dd($shift_name);
			// Output the results
			// var_dump("Login date: " . $login_date . "<br>");
			// var_dump("Shift start: " . $shift_start . "<br>");

			// dd($login_date);
			$alredy_logged_today = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM operator_logins WHERE 
					rnumber = '".$rnumber."' and login_date = '".$login_date."' AND logout_actual is NULL "));
			// dd($alredy_logged_today);

			if (isset($alredy_logged_today[0]->id)) {
				//user already looged today
				
				$shift_start = $alredy_logged_today[0]->shift_start;

				$currentTime = new DateTime();
				$currentTime->format('Y-m-d H:i');

				$logout_motivation = 'Change activity';

				$if_logged_on_same_activity = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [operator_logins]
	  					WHERE activity_id = '".$activity_id."' and rnumber = '".$rnumber."' and logout_actual is null  "));
				// dd($if_logged_on_same_activity);

				if (isset($if_logged_on_same_activity[0]->id)) {
					
					// 
					$activity = DB::connection('sqlsrv')->select(DB::raw("SELECT id, activity_desc FROM activity ORDER BY id asc"));
					$activity = (object) $activity;
					// dd($activity);
					$msg3 = 'Vec ste prijavljeni na istu aktivnost';
					return view('OperatorLogin.change' , compact('activity', 'msg3'));
				}

				$table1 = operatorLogin::findOrFail($alredy_logged_today[0]->id);
				// $table1->key_string = $login_date.'-'.$activity_id.'-'.$rnumber;
				// $table1->rnumber = $rnumber;
				// $table1->operator = $operator;
				// $table1->activity_id = $activity_id;
				// $table1->login_date = $login_date;
				// $table1->login_actual = $currentTime;
				$table1->shift_start = $shift_start;
				// $table1->shift_name = $shift_name;
				$table1->logout_actual = $currentTime;
				$table1->shift_end = $currentTime;
				$table1->logout_motivation = $logout_motivation;
				$table1->save();


				$table = new operatorLogin;
				$table->key_string = $login_date.'-'.$activity_id.'-'.$rnumber;
				$table->rnumber = $rnumber;
				$table->operator = $operator;
				$table->activity_id = $activity_id;
				$table->login_date = $login_date;
				$table->login_actual = $currentTime;
				$table->shift_start = $currentTime;
				$table->shift_name = $shift_name;
				$table->logout_actual;
				$table->shift_end;
				$table->logout_motivation;
				$table->save();


			} else {

				$activity = DB::connection('sqlsrv')->select(DB::raw("SELECT id, activity_desc FROM activity ORDER BY id asc"));
				$activity = (object) $activity;
				// dd($activity);
				$msg3 = 'Promena aktivnosti nije izvrsena jer nije pronadjena prijava operatera';
				return view('OperatorLogin.change' , compact('activity', 'msg3'));
			}


			// $if_logged_from_before = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM operator_logins WHERE rnumber = '".$rnumber."' and logout_actual is null "));
			// if (isset($if_logged_from_before[0]->login_date)) {
			// 	// there is some problem because user was not loged out from before
			// 	dd('there is some problem because user was not loged out from '.substr($if_logged_from_before[0]->login_date,0,10).' ,call IT.');

			// 	$activity = DB::connection('sqlsrv')->select(DB::raw("SELECT id, activity_desc FROM activity ORDER BY id asc"));
			// 	$activity = (object) $activity;
			// 	// dd($activity);
			// 	$msg3 = 'there is some problem because user was not loged out from '.substr($if_logged_from_before[0]->login_date,0,10).' ,call IT.';
			// 	return view('OperatorLogin.login' , compact('activity', 'msg3'));
			// }

			// dd('$shift_name');

		} else {
			
			$activity = DB::connection('sqlsrv')->select(DB::raw("SELECT id, activity_desc FROM activity ORDER BY id asc"));
			$activity = (object) $activity;
			// dd($activity);
			$msg3 = 'Radnik nije pronadjen u Inteos-u !';
			return view('OperatorLogin.change' , compact('activity', 'msg3'));
					
		}
		
		// return Redirect::to('/');
		$activity = DB::connection('sqlsrv')->select(DB::raw("SELECT id, activity_desc FROM activity ORDER BY id asc"));
		$activity = (object) $activity;
		// dd($activity);
		$msg4 = 'Operater '.$operator.' je uspesno promenio aktivnost';
		return view('OperatorLogin.change' , compact('activity', 'msg4'));

	}

// Logout
	public function logout() {
		//

		// $activity = DB::connection('sqlsrv')->select(DB::raw("SELECT id, activity_desc FROM activity ORDER BY id asc"));
		// $activity = (object) $activity;
		// dd($activity);

		$msg3 = '';
		$msg4 = '';

		return view('OperatorLogin.logout' /*, compact('activity')*/);
	}

	public function operator_logout_new_post(Request $request) {

		$input = $request->all(); 
		// dd($input);
		
		$logout_motivation = $input['logout_motivation'];
		$rnumber = $input['rnumber'];
		// dd($logout_motivation);

		$currentTime = new DateTime();
		$currentHourMinute = $currentTime->format('H:i');

		// Determine the login date
		if ($currentHourMinute >= '00:00' && $currentHourMinute < '05:45') {
		    // Between 00:00 and 06:10, use the previous day as login date
		    $login_date = $currentTime->modify('-1 day')->format('Y-m-d');
		} else {
		    // Otherwise, use the current day as login date
		    $login_date = $currentTime->format('Y-m-d');
		}

		$alredy_logged_today = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM operator_logins WHERE 
					rnumber = '".$rnumber."' and login_date = '".$login_date."' AND logout_actual is NULL "));
		// dd($alredy_logged_today);

		if (isset($alredy_logged_today[0]->id)) {
			//user already looged today
			
			$currentTime = new DateTime();
			$currentTime->format('Y-m-d H:i');

			$table1 = operatorLogin::findOrFail($alredy_logged_today[0]->id);
			$table1->logout_actual = $currentTime;
			$table1->shift_end = $currentTime;
			$table1->logout_motivation = $logout_motivation;
			$table1->save();

			// return Redirect::to('/');
			$activity = DB::connection('sqlsrv')->select(DB::raw("SELECT id, activity_desc FROM activity ORDER BY id asc"));
			$activity = (object) $activity;
			// dd($activity);
			$msg4 = 'Operater '.$table1->operator.' se uspesno odjavio';
			return view('OperatorLogin.logout' , compact('activity', 'msg4'));

		} else {

			$activity = DB::connection('sqlsrv')->select(DB::raw("SELECT id, activity_desc FROM activity ORDER BY id asc"));
			$activity = (object) $activity;
			// dd($activity);
			$msg3 = "Odjava operatera nije izvrsena jer nije pronadjena prijava";
			
			return view('OperatorLogin.logout' , compact('activity', 'msg3'));
		}

	}


// Table KIK
	public function table_operator_new_kik() {
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT op.*
				,a.activity_desc
				,a.activity_desc1

			FROM [preproduction].[dbo].[operator_login_kik] as op
			JOIN [preproduction].[dbo].[activity_kik] as a ON a.id = op.activity_id

			WHERE  op.created_at >= DATEADD(day,-30,GETDATE()) 
			ORDER BY op.created_at desc
		"));

		return view('OperatorLogin.table_kik', compact('data'));
	}

// Login KIK
	public function login_kik()	{
		//

		$activity = DB::connection('sqlsrv')->select(DB::raw("SELECT id, activity_desc FROM activity_kik ORDER BY id asc"));
		$activity = (object) $activity;
		// dd($activity);

		$msg3 = '';
		$msg4 = '';

		return view('OperatorLogin.login_kik' , compact('activity'));
	}

	public function operator_login_new_post_kik(Request $request) {
		// $this->validate($request, ['rnumber'=>'required','activity'=>'required']);

		$msg3 = '';
		$msg4 = '';

		
		$input = $request->all(); 
		// dd($input);
		
		$rnumber = strtoupper($input['rnumber']);
		$activity_id = $input['activity'];
		// dd($activity_id);

		if ($activity_id == "0") {
			$activity = DB::connection('sqlsrv')->select(DB::raw("SELECT id, activity_desc FROM activity_kik ORDER BY id asc"));
			$activity = (object) $activity;
			// dd($activity);
			$msg3 = 'Aktivnost mora biti izabrana!';
			return view('OperatorLogin.login_kik' , compact('activity', 'msg3'));
		}

		if ($rnumber == "") {
			$activity = DB::connection('sqlsrv')->select(DB::raw("SELECT id, activity_desc FROM activity_kik ORDER BY id asc"));
			$activity = (object) $activity;
			// dd($activity);
			$msg3 = 'R broj radnika mora biti unesen ili skeniran!';
			return view('OperatorLogin.login_kik' , compact('activity', 'msg3'));
		}
		
		// Find operaot by Rnumber in Inteos
		$inteos = DB::connection('sqlsrv2')->select(DB::raw("SELECT Name FROM WEA_PersData 
			WHERE BadgeNum = :somevariable"), array(
				'somevariable' => $rnumber,
		));

		// dd($inteos);
		// dd($activity_desc);
		
		if (isset($inteos[0]->Name)) {

			$rnumber;
			$activity_id;
			$operator = $inteos[0]->Name;
			$logout_motivation;

			$login_actual = date("Y-m-d H:i:s");
			// dd($login_actual);

			$login_actual_time = date("H:i:s");
			// dd($login_actual_time);

			$currentTime = new DateTime();
			$currentHourMinute = $currentTime->format('H:i');

			// Determine the login date
			if ($currentHourMinute >= '00:00' && $currentHourMinute < '05:45') {
			    // Between 00:00 and 06:10, use the previous day as login date
			    $login_date = $currentTime->modify('-1 day')->format('Y-m-d');
			    $shift_name = '3';
			} else {
			    // Otherwise, use the current day as login date
			    $login_date = $currentTime->format('Y-m-d');
			}

			// Reset $currentTime to the original time (since modify changes the object)
			$currentTime = new DateTime();

			// Determine shift_start based on the specified logic
			if ($currentHourMinute >= '05:45' && $currentHourMinute <= '06:00') {
			    
			    $shift_start = $login_date . ' 06:00';
			    $shift_name = '1';
			} elseif ($currentHourMinute >= '06:00' && $currentHourMinute <= '13:45') {
			    
			    $shift_start = $currentTime->format('Y-m-d H:i');
			    $shift_name = '1';
			} elseif ($currentHourMinute >= '13:45' && $currentHourMinute <= '14:00') {
			    
			    $shift_start = $currentTime->format('Y-m-d') . ' 14:00';
			    $shift_name = '2';
			} elseif ($currentHourMinute >= '14:00' && $currentHourMinute <= '21:45') {
			    
			    $shift_start = $currentTime->format('Y-m-d H:i');
			    $shift_name = '2';
			} elseif ($currentHourMinute >= '21:45' && $currentHourMinute <= '22:00') {
			    
			    $shift_start = $currentTime->format('Y-m-d') . ' 22:00';
			    $shift_name = '3';
			} else {
			    
			    $shift_start = $currentTime->format('Y-m-d H:i');
			    $shift_name = '3';
			}

			
			$alredy_logged_today = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM operator_login_kik WHERE rnumber = '".$rnumber."' and login_date = '".$login_date."' "));

			if (isset($alredy_logged_today[0]->id)) {
				//user already looged today
		
				// previous activity was not closed
				$alredy_logged_today_not_closed_activity = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM operator_login_kik WHERE rnumber = '".$rnumber."' and login_date = '".$login_date."' and logout_actual is null "));

				if (isset($alredy_logged_today_not_closed_activity[0]->id)){
					// user already have logged today and previous acivity is not closed
					// dd("user already have logged today and previous acivity was not finished, please use function change activity");

					$activity = DB::connection('sqlsrv')->select(DB::raw("SELECT id, activity_desc FROM activity_kik ORDER BY id asc"));
					$activity = (object) $activity;
					// dd($activity);
					$msg3 = 'User already have logged today and previous acivity was not finished, please use function change activity';
					return view('OperatorLogin.login_kik' , compact('activity', 'msg3'));
				}

			}


			$if_logged_from_before = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM operator_login_kik WHERE rnumber = '".$rnumber."' and logout_actual is null "));
			if (isset($if_logged_from_before[0]->login_date)) {
				// there is some problem because user was not loged out from before
				// dd('there is some problem because user was not loged out from '.substr($if_logged_from_before[0]->login_date,0,10).' ,call IT.');

				$activity = DB::connection('sqlsrv')->select(DB::raw("SELECT id, activity_desc FROM activity_kik ORDER BY id asc"));
				$activity = (object) $activity;
				// dd($activity);
				$msg3 = 'there is some problem because user was not loged out from '.substr($if_logged_from_before[0]->login_date,0,10).' ,call IT.';
				return view('OperatorLogin.login_kik' , compact('activity', 'msg3'));
			}


			// dd($shift_name);

			$table = new operatorLogin_kik;
			$table->key_string = $login_date.'-'.$activity_id.'-'.$rnumber;
			$table->rnumber = $rnumber;
			$table->operator = $operator;
			$table->activity_id = $activity_id;
			$table->login_date = $login_date;
			$table->login_actual = $currentTime;
			$table->shift_start = $shift_start;
			$table->shift_name = $shift_name;
			$table->logout_actual;
			$table->shift_end;
			$table->logout_motivation;
			$table->save();

		} else {
			
			
			$activity = DB::connection('sqlsrv')->select(DB::raw("SELECT id, activity_desc FROM activity_kik ORDER BY id asc"));
			$activity = (object) $activity;
			// dd($activity);
			$msg3 = 'Radnik nije pronadjen u Inteos-u !';
			return view('OperatorLogin.login_kik' , compact('activity', 'msg3'));
					
		}
		

		// return Redirect::to('/');
		$activity = DB::connection('sqlsrv')->select(DB::raw("SELECT id, activity_desc FROM activity_kik ORDER BY id asc"));
		$activity = (object) $activity;
		// dd($activity);
		$msg4 = 'Operater '.$operator.' uspesno prijavljen';
		return view('OperatorLogin.login_kik' , compact('activity', 'msg4'));
	}

// Change KIK
	public function change_kik() {
		//

		$activity = DB::connection('sqlsrv')->select(DB::raw("SELECT id, activity_desc FROM activity_kik ORDER BY id asc"));
		$activity = (object) $activity;
		// dd($activity);

		$msg3 = '';
		$msg4 = '';

		return view('OperatorLogin.change_kik' , compact('activity'));
	}

	public function operator_change_new_post_kik(Request $request) {

		$msg3 = '';
		$msg4 = '';

		
		$input = $request->all(); 
		// dd($input);
		
		$rnumber = strtoupper($input['rnumber']);
		$activity_id = $input['activity'];
		// dd($activity_id);

		if ($activity_id == "0") {
			$activity = DB::connection('sqlsrv')->select(DB::raw("SELECT id, activity_desc FROM activity_kik ORDER BY id asc"));
			$activity = (object) $activity;
			// dd($activity);
			$msg3 = 'Aktivnost mora biti izabrana!';
			return view('OperatorLogin.change_kik' , compact('activity', 'msg3'));
		}

		if ($rnumber == "") {
			$activity = DB::connection('sqlsrv')->select(DB::raw("SELECT id, activity_desc FROM activity_kik ORDER BY id asc"));
			$activity = (object) $activity;
			// dd($activity);
			$msg3 = 'R broj radnika mora biti unesen ili skeniran!';
			return view('OperatorLogin.change_kik' , compact('activity', 'msg3'));
		}
		
		// Find operaot by Rnumber in Inteos
		$inteos = DB::connection('sqlsrv2')->select(DB::raw("SELECT Name FROM WEA_PersData 
			WHERE BadgeNum = :somevariable"), array(
				'somevariable' => $rnumber,
		));

		// dd($inteos);
		// dd($activity_desc);

		if (isset($inteos[0]->Name)) {

			
			// dd('set');
			// $activity = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM activity WHERE id = '".$activity_id."' "));
			// dd($activity);
			// dd($activity[0]->id);
			// dd($rnumber);

			$rnumber;
			$activity_id;
			$operator = $inteos[0]->Name;
			$logout_motivation;

			$login_actual = date("Y-m-d H:i:s");
			// dd($login_actual);

			$login_actual_time = date("H:i:s");
			// dd($login_actual_time);

			$currentTime = new DateTime();
			$currentHourMinute = $currentTime->format('H:i');

			// Determine the login date
			if ($currentHourMinute >= '00:00' && $currentHourMinute < '05:45') {
			    // Between 00:00 and 06:10, use the previous day as login date
			    $login_date = $currentTime->modify('-1 day')->format('Y-m-d');
			    $shift_name = '3';
			} else {
			    // Otherwise, use the current day as login date
			    $login_date = $currentTime->format('Y-m-d');
			}

			// Reset $currentTime to the original time (since modify changes the object)
			$currentTime = new DateTime();


			// Determine shift_start based on the specified logic
			if ($currentHourMinute >= '05:45' && $currentHourMinute <= '06:00') {
			    
			    $shift_start = $login_date . ' 06:00';
			    $shift_name = '1';
			} elseif ($currentHourMinute >= '06:00' && $currentHourMinute <= '13:45') {
			    
			    $shift_start = $currentTime->format('Y-m-d H:i');
			    $shift_name = '1';
			} elseif ($currentHourMinute >= '13:45' && $currentHourMinute <= '14:00') {
			    
			    $shift_start = $currentTime->format('Y-m-d') . ' 14:00';
			    $shift_name = '2';
			} elseif ($currentHourMinute >= '14:00' && $currentHourMinute <= '21:45') {
			    
			    $shift_start = $currentTime->format('Y-m-d H:i');
			    $shift_name = '2';
			} elseif ($currentHourMinute >= '21:45' && $currentHourMinute <= '22:00') {
			    
			    $shift_start = $currentTime->format('Y-m-d') . ' 22:00';
			    $shift_name = '3';
			} else {
			    
			    $shift_start = $currentTime->format('Y-m-d H:i');
			    $shift_name = '3';
			}

			// dd($login_date);
			$alredy_logged_today = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM operator_login_kik WHERE 
					rnumber = '".$rnumber."' and login_date = '".$login_date."' AND logout_actual is NULL "));
			// dd($alredy_logged_today);

			if (isset($alredy_logged_today[0]->id)) {
				//user already looged today

				$shift_start = $alredy_logged_today[0]->shift_start;

				$currentTime = new DateTime();
				$currentTime->format('Y-m-d H:i');

				$logout_motivation = 'Change activity';

				$if_logged_on_same_activity = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [operator_login_kik]
	  					WHERE activity_id = '".$activity_id."' and rnumber = '".$rnumber."' and logout_actual is null  "));
				// dd($if_logged_on_same_activity);

				if (isset($if_logged_on_same_activity[0]->id)) {
					
					// 
					$activity = DB::connection('sqlsrv')->select(DB::raw("SELECT id, activity_desc FROM activity_kik ORDER BY id asc"));
					$activity = (object) $activity;
					// dd($activity);
					$msg3 = 'Vec ste prijavljeni na istu aktivnost';
					return view('OperatorLogin.change_kik' , compact('activity', 'msg3'));
				}

				$table1 = operatorLogin_kik::findOrFail($alredy_logged_today[0]->id);
				// $table1->key_string = $login_date.'-'.$activity_id.'-'.$rnumber;
				// $table1->rnumber = $rnumber;
				// $table1->operator = $operator;
				// $table1->activity_id = $activity_id;
				// $table1->login_date = $login_date;
				// $table1->login_actual = $currentTime;
				$table1->shift_start = $shift_start;
				// $table1->shift_name = $shift_name;
				$table1->logout_actual = $currentTime;
				$table1->shift_end = $currentTime;
				$table1->logout_motivation = $logout_motivation;
				$table1->save();


				$table = new operatorLogin_kik;
				$table->key_string = $login_date.'-'.$activity_id.'-'.$rnumber;
				$table->rnumber = $rnumber;
				$table->operator = $operator;
				$table->activity_id = $activity_id;
				$table->login_date = $login_date;
				$table->login_actual = $currentTime;
				$table->shift_start = $currentTime;
				$table->shift_name = $shift_name;
				$table->logout_actual;
				$table->shift_end;
				$table->logout_motivation;
				$table->save();


			} else {

				$activity = DB::connection('sqlsrv')->select(DB::raw("SELECT id, activity_desc FROM activity_kik ORDER BY id asc"));
				$activity = (object) $activity;
				// dd($activity);
				$msg3 = 'Promena aktivnosti nije izvrsena jer nije pronadjena prijava operatera';
				return view('OperatorLogin.change_kik' , compact('activity', 'msg3'));
			}


			// $if_logged_from_before = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM operator_logins WHERE rnumber = '".$rnumber."' and logout_actual is null "));
			// if (isset($if_logged_from_before[0]->login_date)) {
			// 	// there is some problem because user was not loged out from before
			// 	dd('there is some problem because user was not loged out from '.substr($if_logged_from_before[0]->login_date,0,10).' ,call IT.');

			// 	$activity = DB::connection('sqlsrv')->select(DB::raw("SELECT id, activity_desc FROM activity ORDER BY id asc"));
			// 	$activity = (object) $activity;
			// 	// dd($activity);
			// 	$msg3 = 'there is some problem because user was not loged out from '.substr($if_logged_from_before[0]->login_date,0,10).' ,call IT.';
			// 	return view('OperatorLogin.login' , compact('activity', 'msg3'));
			// }

			// dd('$shift_name');

		} else {
			
			$activity = DB::connection('sqlsrv')->select(DB::raw("SELECT id, activity_desc FROM activity_kik ORDER BY id asc"));
			$activity = (object) $activity;
			// dd($activity);
			$msg3 = 'Radnik nije pronadjen u Inteos-u !';
			return view('OperatorLogin.change_kik' , compact('activity', 'msg3'));
					
		}
		
		// return Redirect::to('/');
		$activity = DB::connection('sqlsrv')->select(DB::raw("SELECT id, activity_desc FROM activity_kik ORDER BY id asc"));
		$activity = (object) $activity;
		// dd($activity);
		$msg4 = 'Operater '.$operator.' je uspesno promenio aktivnost';
		return view('OperatorLogin.change_kik' , compact('activity', 'msg4'));

	}

// Logout KIK
	public function logout_kik() {
		//

		// $activity = DB::connection('sqlsrv')->select(DB::raw("SELECT id, activity_desc FROM activity ORDER BY id asc"));
		// $activity = (object) $activity;
		// dd($activity);

		$msg3 = '';
		$msg4 = '';

		return view('OperatorLogin.logout_kik' /*, compact('activity')*/);
	}

	public function operator_logout_new_post_kik(Request $request) {

		$input = $request->all(); 
		// dd($input);
		
		$logout_motivation = $input['logout_motivation'];
		$rnumber = $input['rnumber'];
		// dd($logout_motivation);

		$currentTime = new DateTime();
		$currentHourMinute = $currentTime->format('H:i');

		// Determine the login date
		if ($currentHourMinute >= '00:00' && $currentHourMinute < '05:45') {
		    // Between 00:00 and 06:10, use the previous day as login date
		    $login_date = $currentTime->modify('-1 day')->format('Y-m-d');
		} else {
		    // Otherwise, use the current day as login date
		    $login_date = $currentTime->format('Y-m-d');
		}

		$alredy_logged_today = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM operator_login_kik WHERE 
					rnumber = '".$rnumber."' and login_date = '".$login_date."' AND logout_actual is NULL "));
		// dd($alredy_logged_today);

		if (isset($alredy_logged_today[0]->id)) {
			//user already looged today
			
			$currentTime = new DateTime();
			$currentTime->format('Y-m-d H:i');

			$table1 = operatorLogin_kik::findOrFail($alredy_logged_today[0]->id);
			$table1->logout_actual = $currentTime;
			$table1->shift_end = $currentTime;
			$table1->logout_motivation = $logout_motivation;
			$table1->save();

			// return Redirect::to('/');
			$activity = DB::connection('sqlsrv')->select(DB::raw("SELECT id, activity_desc FROM activity_kik ORDER BY id asc"));
			$activity = (object) $activity;
			// dd($activity);
			$msg4 = 'Operater '.$table1->operator.' se uspesno odjavio';
			return view('OperatorLogin.logout_kik' , compact('activity', 'msg4'));

		} else {

			$activity = DB::connection('sqlsrv')->select(DB::raw("SELECT id, activity_desc FROM activity_kik ORDER BY id asc"));
			$activity = (object) $activity;
			// dd($activity);
			$msg3 = "Odjava operatera nije izvrsena jer nije pronadjena prijava";
			
			return view('OperatorLogin.logout_kik' , compact('activity', 'msg3'));
		}

	}


}
