<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use DateTime;
use Carbon\Carbon;

use App\login_operator;
use App\login_preproduction;
use App\activity;
use DB;

class LoginPreproductionController extends Controller {


	public function preproduction_login()
	{
		//
		// dd('cao');
		
		$msg1 = '';
		$msg2 = '';
		$msg3 = '';
		$msg4 = '';

		return view('preLogin.index', compact('msg1','msg2','msg3','msg4'));
	}

	public function preproduction_login_post(Request $request) {
		// $this->validate($request, ['rnumber'=>'required','activity'=>'required']);

		$msg1 = '';
		$msg2 = '';
		$msg3 = '';
		$msg4 = '';

		$input = $request->all(); 
		// dd($input);
		
		if (isset($input['smena'][0])) {
			$smena = $input['smena'][0];
		} else {
			$msg1 = 'Izaberite smenu, please choose shift. ';
			// dd($msg1);
			return view('preLogin.index' , compact('msg1'));
		}
		// dd($smena);

		if (isset($input['rnumber']) AND ($input['rnumber'] != '')) {
			$rnumber = strtoupper($input['rnumber']);
		} else {
			$msg2 = 'Skenirajte karticu operatera, please scan operator card (R number)';
			return view('preLogin.index' , compact('msg2','smena'));
		}
		// dd($rnumber);

		$inteos = DB::connection('sqlsrv1')->select(DB::raw("SELECT Name FROM BdkCLZG.dbo.WEA_PersData WHERE BadgeNum = :somevariable
				 "), array(
				'somevariable' => $rnumber
		));

		if (!isset($inteos[0]->Name)) {
			
			$msg3 = 'Operator does not exist';
			return view('preLogin.index' , compact('msg3','smena'));

		} else {

			// dd(date("H:i:s"));

			if ($smena == 'I') {
				if (date("H:i:s") < date("06:30:00")) {
					// dd('Less');
					$login_date = date("Y-m-d 06:00:00");
				} else if (date("H:i:s") > date("10:00:00")) {

					return view('preLogin.index2',compact('msg5','smena','rnumber'));

				} else {
					// dd('More');
					$login_date = date("Y-m-d H:i:s");
				}

			} else if ($smena == 'II') {
				if (date("H:i:s") < date("14:30:00")) {
					// dd('Less');
					$login_date = date("Y-m-d 14:00:00");
				} else if (date("H:i:s") > date("10:00:00")) {

					return view('preLogin.index2',compact('msg5','smena','rnumber'));
				} else {
					// dd('More');
					$login_date = date("Y-m-d H:i:s");
				}
				
			} else if ($smena == 'M') {
				if (date("H:i:s") < date("07:30:00")) {
					// dd('Less');
					$login_date = date("Y-m-d 07:00:00");
				} else if (date("H:i:s") > date("10:00:00")) {

					return view('preLogin.index2',compact('msg5','smena','rnumber'));
				} else {
					// dd('More');
					$login_date = date("Y-m-d H:i:s");
				}
			}
			$key = date("Y-m-d").'_'.$rnumber;
			// dd($key);

			try {
				// $table = new login_preproduction;
				$table = login_preproduction::firstOrNew(['key' => $key]);
				
				$table->key = $key;

				$table->rnumber = $rnumber;
				$table->operator = $inteos[0]->Name;

				$table->shift = $smena;

				$table->login_date = $login_date;

				$table->save();
			}
			catch (\Illuminate\Database\QueryException $e) {
				dd('Greska, zovi IT'.$e);
				
			}
		}

		$msg4 = $inteos[0]->Name;
		return view('preLogin.index',compact('msg4', 'smena'));

	}

	public function preproduction_login_post2(Request $request) {
		// $this->validate($request, ['rnumber'=>'required','activity'=>'required']);

		$msg1 = '';
		$msg2 = '';
		$msg3 = '';
		$msg4 = '';

		$input = $request->all(); 
		// dd($input);

		if (isset($input['smena'][0])) {
			$smena = $input['smena'][0];
		} else {
			$msg1 = 'Izaberite smenu, please choose shift. ';
			// dd($msg1);
			return view('preLogin.index' , compact('msg1'));
		}
		// dd($smena);

		if (isset($input['rnumber']) AND ($input['rnumber'] != '')) {
			$rnumber = strtoupper($input['rnumber']);
		} else {
			$msg2 = 'Skenirajte karticu operatera, please scan operator card (R number)';
			return view('preLogin.index' , compact('msg2','smena'));
		}
		// dd($rnumber);

		if (isset($input['pauza'][0])) {

			$pauza = $input['pauza'][0];
		} else {

			$msg1 = 'Izaberite opciju da li je pauza prosla ili ne.';
			// dd($msg1);
			return view('preLogin.index2' , compact('msg5','smena'));
		}
		// dd($pauza);

		$inteos = DB::connection('sqlsrv1')->select(DB::raw("SELECT Name FROM BdkCLZG.dbo.WEA_PersData WHERE BadgeNum = :somevariable
				 "), array(
				'somevariable' => $rnumber
		));

		if (!isset($inteos[0]->Name)) {
			
			$msg3 = 'Operator does not exist';
			return view('preLogin.index' , compact('msg3','smena'));

		} else {

			// dd(date("H:i:s"));
			if ($smena == 'I') {
				if (date("H:i:s") < date("06:30:00")) {
					// dd('Less');
					$login_date = date("Y-m-d 06:00:00");
				} else if (date("H:i:s") > date("09:40:00")) {

					if ($pauza == 'da' ) {
			
						$pauza_b = 0;
					} else {
						
						$pauza_b = 1;	
					}
					$login_date = date("Y-m-d H:i:s");

				} else {
					// dd('More');
					$login_date = date("Y-m-d H:i:s");
				}

			} else if ($smena == 'II') {
				if (date("H:i:s") < date("14:30:00")) {
					// dd('Less');
					$login_date = date("Y-m-d 14:00:00");
				} else if (date("H:i:s") > date("09:40:00")) {

					if ($pauza == 'da' ) {
			
						$pauza_b = 0;
					} else {
						
						$pauza_b = 1;	
					}
					$login_date = date("Y-m-d H:i:s");
					
				} else {
					// dd('More');
					$login_date = date("Y-m-d H:i:s");
				}
				
			} else if ($smena == 'M') {
				if (date("H:i:s") < date("07:30:00")) {
					// dd('Less');
					$login_date = date("Y-m-d 07:00:00");
				} else if (date("H:i:s") > date("09:40:00")) {

					if ($pauza == 'da' ) {
			
						$pauza_b = 0;
					} else {
						
						$pauza_b = 1;	
					}
					$login_date = date("Y-m-d H:i:s");

				} else {
					// dd('More');
					$login_date = date("Y-m-d H:i:s");
				}
			}
			$key = date("Y-m-d").'_'.$rnumber;
			// dd($key);

			try {
				// $table = new login_preproduction;
				$table = login_preproduction::firstOrNew(['key' => $key]);
				
				$table->key = $key;

				$table->rnumber = $rnumber;
				$table->operator = $inteos[0]->Name;

				$table->shift = $smena;

				if ($pauza_b == 0 OR $pauza_b == 1) {
					$table->break = $pauza_b;	
				}
				
				$table->login_date = $login_date;

				$table->save();
			}
			catch (\Illuminate\Database\QueryException $e) {
				dd('Greska, zovi IT'.$e);
				
			}
		}

		$msg4 = $inteos[0]->Name;
		return view('preLogin.index',compact('msg4', 'smena'));
	}

	public function preproduction_logout() {
		// dd('dd');

		$msg1 = '';
		$msg2 = '';
		$msg3 = '';
		$msg4 = '';

		return view('preLogout.index', compact('msg1','msg2','msg3','msg4'));
	}

	public function preproduction_logout_post(Request $request) {
		// $this->validate($request, ['rnumber'=>'required','activity'=>'required']);
		// dd("Cao");

		$msg1 = '';
		$msg2 = '';
		$msg3 = '';
		$msg4 = '';

		$input = $request->all(); 
		// dd($input);
		
		if (isset($input['pauza'][0])) {

			$pauza = $input['pauza'][0];
		} else {

			$msg1 = 'Izaberite opciju da li je pauza prosla ili ne';
			// dd($msg1);
			return view('preLogout.index' , compact('msg1'));
		}
		// dd($pauza);

		if (isset($input['rnumber']) AND ($input['rnumber'] != '')) {
			$rnumber = strtoupper($input['rnumber']);
		} else {
			$msg2 = 'Skenirajte karticu operatera, please scan operator card (R number)';
			return view('preLogout.index' , compact('msg2','pauza'));
		}
		// dd($rnumber);

		$inteos = DB::connection('sqlsrv1')->select(DB::raw("SELECT Name FROM BdkCLZG.dbo.WEA_PersData WHERE BadgeNum = :somevariable
				 "), array(
				'somevariable' => $rnumber
		));

		if (!isset($inteos[0]->Name)) {
			
			$msg3 = 'Operater sa tim brojem ne postoji, operator does not exist';
			return view('preLogout.index' , compact('msg3','pauza'));

		} else {

			$key = date("Y-m-d").'_'.$rnumber;
			// dd($key);

			if ($pauza == 'da') {
				$pauza_b = 1;	
				$pauza_min = 30;
			} else {
				$pauza_b = 0;	
				$pauza_min = 0;
			}
			
			try {

				$table = login_preproduction::where('key', '=', $key)->firstOrFail();
				// dd($smena->shift);

				// $table = new login_preproduction;
				// $table = login_preproduction::firstOrNew(['key' => $key]);
				
				// $table->key = $key;

				// $table->rnumber = $rnumber;
				// $table->operator = $inteos[0]->Name;

				// $table->shift = $smena;

				// $table->login_date = $login_date;
				$table->logout_date = date("Y-m-d H:i:s");

				$total_time = strtotime($table->logout_date) - strtotime($table->login_date);

				// if ($pauza == 'da' AND $total_time > 1800) {
				// 	$pauza_min = 0;
				// 	$pauza_b = 0;
				// } else if ($pauza == 'da' AND $total_time < 1800) {
				// 	$pauza_min = 30;
				// 	$pauza_b = 1;	
				// }

				$table->total_time = round(($total_time/60)-$pauza_min, 2); //in min
				// dd($table->total_time);

				$table->break = $pauza_b;
				$table->save();

			}

			catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
				dd('Greska, ne postoji skeniran operater danas koji bi mogao sa se odjavi! Pogledati tabelu registracija za danas.');
			}
			catch (\Illuminate\Database\QueryException $e) {
				dd('Greska, ne postoji skeniran operater danas koji bi mogao sa se odjavi! Pogledati tabelu registracija za danas.');
			}
				
		}

		$msg4 = $inteos[0]->Name;
		return view('preLogout.index',compact('msg4', 'pauza'));
	
	}

	public function table_preproduction() {
		// dd('Cao');

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM login_preproductions WHERE  created_at >= DATEADD(day,-30,GETDATE()) ORDER BY created_at desc"));
		// dd($data);
		return view('preLogin.table', compact('data'));

	}

	public function edit_line($id) {

		//
		// dd($id);
		return view('preLogin.edit_line', compact('id'));		
	}

	public function edit_line_post(Request $request) {

		$input = $request->all(); 
		// dd($input);
		
		$id = $input['id'];
		$lozinka = $input['lozinka'];
		
		// dd('id: '.$id.' , lozinka: '.$lozinka);

		if ($lozinka != '2403')	{

			$msg = 'Pogresna lozinka! Pokusaljte ponovo.';
			return view('preLogin.edit_line', compact('id', 'msg'));

		} else {

			$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM login_preproductions WHERE id = '".$id."' "));
			// dd($data[0]->downtime);

			$downtime = round($data[0]->downtime,0);
			// dd($downtime);
			return view('preLogin.edit_line_set', compact('id','downtime'));
		}
	}
	
	public function edit_line_set(Request $request) {

		$input = $request->all(); 
		// dd($input);
		
		$id = $input['id'];
		$downtime = round((int)$input['downtime'], 0);

		$table = login_preproduction::where('id', '=', $id)->firstOrFail();
		

		if ((int)$table->total_time != 0) {
			
			// dd((int)$table->total_time);
			$table->total_time = $table->total_time - $downtime;

		} else {


		}

		$table->downtime = $downtime; 
		$table->save();


		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM login_preproductions WHERE  created_at >= DATEADD(day,-30,GETDATE()) ORDER BY created_at desc"));
		// dd($data);
		return view('preLogin.table', compact('data'));
		
	}

	public function remove_line(Request $request) {

		$input = $request->all(); 
		// dd($input);
		
		$id = $input['id'];

		return view('preLogin.remove_line', compact('id'));
	}

	public function remove_line_post(Request $request) {

		$input = $request->all(); 
		// dd($input);
		
		$id = $input['id'];

		$table = login_preproduction::where('id', '=', $id)->firstOrFail();
		$table->delete();


		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM login_preproductions WHERE  created_at >= DATEADD(day,-30,GETDATE()) ORDER BY created_at desc"));
		// dd($data);
		return view('preLogin.table', compact('data'));	
	}

}
