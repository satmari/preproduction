<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use DateTime;
use Carbon\Carbon;

use App\operatorLogin;
use App\activity;
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

		$msg1 = '';
		$msg2 = '';
		$msg3 = '';
		$msg4 = '';

		return view('OperatorLogin.login' , compact('activity'));
	}

	public function operator_login_new_post(Request $request) {
		// $this->validate($request, ['rnumber'=>'required','activity'=>'required']);

		$msg1 = '';
		$msg2 = '';
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
			$msg1 = 'Aktivnost mora biti izabrana!';
			return view('OperatorLogin.login' , compact('activity', 'msg1'));
		}

		if ($rnumber == "") {
			$activity = DB::connection('sqlsrv')->select(DB::raw("SELECT id, activity_desc FROM activity ORDER BY id asc"));
			$activity = (object) $activity;
			// dd($activity);
			$msg2 = 'R broj radnika mora biti unesen ili skeniran!';
			return view('OperatorLogin.login' , compact('activity', 'msg2'));
		}
		
		// Find operaot by Rnumber in Inteos
		$inteos = DB::connection('sqlsrv1')->select(DB::raw("SELECT Name FROM BdkCLZG.dbo.WEA_PersData 
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
			if ($currentHourMinute >= '00:00' && $currentHourMinute < '06:00') {
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
				// dd('user already looged today');
				
				// same activity
				// $alredy_logged_today_activity = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM operator_logins WHERE rnumber = '".$rnumber."' and login_date = '".$login_date."' and activity_id = '".$activity_id."' "));

				// if (isset($alredy_logged_today_activity[0]->id)) {
				// 	// user already have logged today with this activity
				// 	dd("user already have logged today with this activity");
				// }

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

		$msg1 = '';
		$msg2 = '';
		$msg3 = '';
		$msg4 = '';

		return view('OperatorLogin.change' , compact('activity'));
	}

	public function operator_change_new_post(Request $request) {

		$msg1 = '';
		$msg2 = '';
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
			$msg1 = 'Aktivnost mora biti izabrana!';
			return view('OperatorLogin.login' , compact('activity', 'msg1'));
		}

		if ($rnumber == "") {
			$activity = DB::connection('sqlsrv')->select(DB::raw("SELECT id, activity_desc FROM activity ORDER BY id asc"));
			$activity = (object) $activity;
			// dd($activity);
			$msg2 = 'R broj radnika mora biti unesen ili skeniran!';
			return view('OperatorLogin.login' , compact('activity', 'msg2'));
		}
		
		// Find operaot by Rnumber in Inteos
		$inteos = DB::connection('sqlsrv1')->select(DB::raw("SELECT Name FROM BdkCLZG.dbo.WEA_PersData 
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
			if ($currentHourMinute >= '00:00' && $currentHourMinute < '06:00') {
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

			
			$alredy_logged_today = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM operator_logins WHERE 
					rnumber = '".$rnumber."' and login_date = '".$login_date."' AND logout_actual is NULL "));


			if (isset($alredy_logged_today[0]->id)) {
				//user already looged today
				// dd('user already looged today');
				
				// same activity
				// $alredy_logged_today_activity = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM operator_logins WHERE rnumber = '".$rnumber."' and login_date = '".$login_date."' and activity_id = '".$activity_id."' "));

				// if (isset($alredy_logged_today_activity[0]->id)) {
				// 	// user already have logged today with this activity
				// 	dd("user already have logged today with this activity");
				// }

				// previous activity was not closed
				// $alredy_logged_today_not_closed_activity = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM operator_logins WHERE rnumber = '".$rnumber."' and login_date = '".$login_date."' and logout_actual is null "));

				// if (isset($alredy_logged_today_not_closed_activity[0]->id)){
				// 	// user already have logged today and previous acivity is not closed
				// 	// dd("user already have logged today and previous acivity was not finished, please use function change activity");

				// 	$activity = DB::connection('sqlsrv')->select(DB::raw("SELECT id, activity_desc FROM activity ORDER BY id asc"));
				// 	$activity = (object) $activity;
				// 	// dd($activity);
				// 	$msg3 = 'User already have logged today and previous acivity was not finished, please use function change activity';
				// 	return view('OperatorLogin.login' , compact('activity', 'msg3'));
				// }

				$shift_start = $alredy_logged_today[0]->shift_start;

				$currentTime = new DateTime();
				$currentTime->format('Y-m-d H:i');

				$logout_motivation = 'Change activity';

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
				// $table1->shift_end;
				$table1->logout_motivation = $logout_motivation;
				$table1->save();


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

		$msg1 = '';
		$msg2 = '';
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
		if ($currentHourMinute >= '00:00' && $currentHourMinute < '06:00') {
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
			// dd('user already looged today');
			
			// same activity
			// $alredy_logged_today_activity = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM operator_logins WHERE rnumber = '".$rnumber."' and login_date = '".$login_date."' and activity_id = '".$activity_id."' "));

			// if (isset($alredy_logged_today_activity[0]->id)) {
			// 	// user already have logged today with this activity
			// 	dd("user already have logged today with this activity");
			// }

			// previous activity was not closed
			// $alredy_logged_today_not_closed_activity = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM operator_logins WHERE rnumber = '".$rnumber."' and login_date = '".$login_date."' and logout_actual is null "));

			// if (isset($alredy_logged_today_not_closed_activity[0]->id)){
			// 	// user already have logged today and previous acivity is not closed
			// 	// dd("user already have logged today and previous acivity was not finished, please use function change activity");

			// 	$activity = DB::connection('sqlsrv')->select(DB::raw("SELECT id, activity_desc FROM activity ORDER BY id asc"));
			// 	$activity = (object) $activity;
			// 	// dd($activity);
			// 	$msg3 = 'User already have logged today and previous acivity was not finished, please use function change activity';
			// 	return view('OperatorLogin.login' , compact('activity', 'msg3'));
			// }

			
			$currentTime = new DateTime();
			$currentTime->format('Y-m-d H:i');

			$table1 = operatorLogin::findOrFail($alredy_logged_today[0]->id);
			// $table1->key_string = $login_date.'-'.$activity_id.'-'.$rnumber;
			// $table1->rnumber = $rnumber;
			// $table1->operator = $operator;
			// $table1->activity_id = $activity_id;
			// $table1->login_date = $login_date;
			// $table1->login_actual = $currentTime;
			// $table1->shift_name = $shift_name;
			$table1->logout_actual = $currentTime;
			// $table1->shift_end;
			$table1->logout_motivation = $logout_motivation;
			$table1->save();


		} else {

			$activity = DB::connection('sqlsrv')->select(DB::raw("SELECT id, activity_desc FROM activity ORDER BY id asc"));
			$activity = (object) $activity;
			// dd($activity);
			$msg3 = 'Odjava operatera nije izvrsena jer nije pronadjena prijava';
			return view('OperatorLogin.logout' , compact('activity', 'msg3'));
		}

		// return Redirect::to('/');
		$activity = DB::connection('sqlsrv')->select(DB::raw("SELECT id, activity_desc FROM activity ORDER BY id asc"));
		$activity = (object) $activity;
		// dd($activity);
		$msg4 = 'Operater '.$operator.' se uspesno odjavio';
		return view('OperatorLogin.logout' , compact('activity', 'msg4'));

	}




}
