<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use DateTime;
use Carbon\Carbon;

use App\login_operator;
use App\activity;
use App\activity_kik;
use App\activity_wh;
use App\activity_bon;
use App\activity_sec;

use DB;

class ActivityController extends Controller {

// Cutting
	public function index() {
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

// Kikinda

	public function index_kik() {
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

// Warehouse

	public function index_wh() {
		// dd("test");

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM activity_wh ORDER BY id"));
		return view('Activity.index_wh', compact('data'));
	}

	public function add_activity_wh () {
		//
		// dd("test");
		return view('Activity.add_activity_wh');
	}

	public function insert_activity_wh (Request $request) {
		
		//
		$this->validate($request, ['activity_desc' => 'required']);
		$input = $request->all();
		
		try {
			$table = new activity_wh;

			$table->activity_desc = $input['activity_desc'];
			$table->activity_desc1 = $input['activity_desc1'];
									
			$table->save();
		}
		catch (\Illuminate\Database\QueryException $e) {
			$msg = "Problem to save in activity table";
			return view('Activity.error_wh',compact('msg'));
		}

		return Redirect::to('/activity_wh');
	}

// Bonding

	public function index_bon() {
		// dd("test");

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * 
			FROM activity_bon ORDER BY id"));
		return view('Activity.index_bon', compact('data'));
	}

	public function add_activity_bon () {
		//
		// dd("test");
		return view('Activity.add_activity_bon');
	}

	public function insert_activity_bon (Request $request) {
		
		//
		$this->validate($request, ['activity_desc' => 'required']);
		$input = $request->all();
		
		try {
			$table = new activity_bon;

			$table->activity_desc = $input['activity_desc'];
			$table->activity_desc1 = $input['activity_desc1'];
									
			$table->save();
		}
		catch (\Illuminate\Database\QueryException $e) {
			$msg = "Problem to save in activity table";
			return view('Activity.error_bon',compact('msg'));
		}

		return Redirect::to('/activity_bon');
	}


// SecondQ

	public function index_secs() {
		// dd("test");

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM activity_secs ORDER BY id"));
		return view('Activity.index_secs', compact('data'));
	}

	public function add_activity_secs () {
		//
		// dd("test");
		return view('Activity.add_activity_secs');
	}

	public function insert_activity_secs (Request $request) {
		
		//
		$this->validate($request, ['activity_desc' => 'required']);
		$input = $request->all();
		
		try {
			$table = new activity_sec;

			$table->activity_desc = $input['activity_desc'];
			$table->activity_desc1 = $input['activity_desc1'];
									
			$table->save();
		}
		catch (\Illuminate\Database\QueryException $e) {
			$msg = "Problem to save in activity table";
			return view('Activity.error_secs',compact('msg'));
		}

		return Redirect::to('/activity_secs');
	}

}
