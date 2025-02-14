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

class ActivityController extends Controller {

	public function index()
	{
		//
		// dd("test");

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM activity ORDER BY id"));
		return view('Activity.index', compact('data'));
	}

	public function add_activity () {
		//
		// dd("test");
		return view('Activity.add_activity');
	}

	public function insert_activity (Request $request) {
		
		//
		$this->validate($request, ['activity_desc' => 'required']);
		$input = $request->all();

		try {
			$table = new activity;

			$table->activity_desc = $input['activity_desc'];
			$table->activity_desc1 = $input['activity_desc1'];
									
			$table->save();
		}
		catch (\Illuminate\Database\QueryException $e) {
			$msg = "Problem to save in activity table";
			return view('Activity.error',compact('msg'));
		}

		return Redirect::to('/activity');


	}

	public function index_kik()
	{
		//
		// dd("test");

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM activity_kik ORDER BY id"));
		return view('Activity.index_kik', compact('data'));
	}

	public function add_activity_kik () {
		//
		// dd("test");
		return view('Activity.add_activity_kik');
	}

	public function insert_activity_kik (Request $request) {
		
		//
		$this->validate($request, ['activity_desc' => 'required']);
		$input = $request->all();

		try {
			$table = new activity_kik;

			$table->activity_desc = $input['activity_desc'];
			$table->activity_desc1 = $input['activity_desc1'];
									
			$table->save();
		}
		catch (\Illuminate\Database\QueryException $e) {
			$msg = "Problem to save in activity table";
			return view('Activity.error_kik',compact('msg'));
		}

		return Redirect::to('/activity_kik');


	}

}
